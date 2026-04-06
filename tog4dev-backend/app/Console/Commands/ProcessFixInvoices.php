<?php

namespace App\Console\Commands;

use App\Mail\ErrorEmail;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PDF;
use App\Models\Payment;
use App\Mail\PaymentReceiptMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class ProcessFixInvoices extends Command
{
    protected $signature = 'app:process-fix-invocies';
    protected $description = 'Process to fix payments invoices where payment method ORANGE MONEY, CASH, BANK, ZBOONI USA';

    public function handle()
    {

        $lock = Cache::lock('payment-fix-invoices-lock', 300000);
        if ($lock->get()) {
            try {
                $payments = Payment::where('status', 'approved')
                    ->where('created_at', 'like', '%2024-%')
                    ->whereIn('payment_type', ['BANK', 'CASH', 'ORANGE MONEY', 'ZBOONI USA'])
                    ->take(100)
                    ->get();
                $this->processPaymentCliQ($payments);
            } finally {
                $lock->release(); // Release the lock after execution
            }
        } else {
            \Log::info('Cron job is already running.');
        }
    }

    public function processPaymentCliQ($payments){
        foreach ($payments as $payment) {
            try {
                $subscription = 0;
                
                $user = $payment->userDetails;

                $full_country = $this->getTranslatedCountry($user->country);

                if($payment->contract_id == null){
                    $lastContract = Payment::whereNotNull('contract_id')->max('contract_id');

                    if ($lastContract) {
                        // Extract the numeric part and increment it
                        $number = (int) Str::after($lastContract, 'W-');
                        $newContractId = 'W-' . str_pad($number + 1, 5, '0', STR_PAD_LEFT);
                    } else {
                        // If no contract exists, start with W-00001
                        $newContractId = 'W-00001';
                    }
                    
                    $payment->update([
                        'contract_id' => $newContractId,
                    ]);
                } else {
                    $newContractId = $payment->contract_id;
                }

                $carts = Cart::where('payment_id', $payment->id)
                    ->where('is_paid', 1)
                    ->with('model') // Load related model data
                    ->get();

                if($carts->count() == 0 || $carts->count() == null){
                    Mail::to(env('ERROR_EMAIL'))->send(new ErrorEmail($payment));
                    continue;
                }

                foreach ($carts as $cart) {
                    if ($cart->type === 'monthly') {
                        $subscription = 1;
                        break;
                    }
                }

                $locale = $payment->lang;

                app()->setLocale($locale);

                if ($locale === 'ar') {
                    $emailInvoice = 'emails.invoice';
                    $emailContract = 'emails.contract';
                    $privacy_policy_name = "سياسات-الإشتراكات.pdf";
                } else {
                    $emailInvoice = 'emails.invoice_en';
                    $emailContract = 'emails.contract_en';
                    $privacy_policy_name = "Subscriptions-Policy.pdf";
                }

                // Generate and save invoice PDF
                $pdf = PDF::loadView($emailInvoice, ['payment' => $payment, 'user' => $user, 'carts' => $carts, 'numberToWords' => 'numberToWords'], [], [
                    'format' => 'A4-L',
                ]);

                Storage::makeDirectory("cliq/2024/99/".$payment->tran_ref);
                
                $invoicePath = storage_path('app/invoices/invoice_' . $payment->cart_id . '.pdf');
                $invoiceCliq = storage_path('app/cliq/2024/99/'.$payment->tran_ref.'/invoice-' . $newContractId . '.pdf');
                $pdf->save($invoicePath);
                $pdf->save($invoiceCliq);

                $payment->addMedia($invoicePath)
                    ->toMediaCollection('invoices');

                // Generate and save contract PDF
                $contractPDF = PDF::loadView($emailContract, ['payment' => $payment, 'carts' => $carts, 'country' => $full_country], [], [
                    'default_font' => '"Readex Pro", sans-serif',
                ]);

                $contractPath = storage_path('app/contracts/contract_' . $payment->cart_id . '.pdf');
                $contractCliq = storage_path('app/cliq/2024/99/'.$payment->tran_ref.'/contract-' . $newContractId . '.pdf');
                $contractPDF->save($contractPath);
                $contractPDF->save($contractCliq);

                $payment->addMedia($contractPath)
                    ->toMediaCollection('contracts');

                if($subscription){
                    // Full path to the source file
                    $source = base_path('resources/views/emails/privacy_policy.pdf');

                    // Destination path in the storage/app folder
                    $destination = storage_path('app/cliq/2024/99/'.$payment->tran_ref.'/'.$privacy_policy_name);

                    // Copy the file
                    File::copy($source, $destination);
                }

                // Clean up temporary files after email is sent
                if (file_exists($invoicePath)) {
                    unlink($invoicePath);
                }
                if (file_exists($contractPath)) {
                    unlink($contractPath);
                }

                $payment->update([
                    'send_email' => 1,
                ]);
            } catch (\Exception $e) {
                $this->error("Error processing payment {$payment->cart_id}: " . $e->getMessage());
            }
        }
    }

    public function getTranslatedCountry($country)
    {
        $filePath = public_path('countries.json');

        // Check if the file exists
        if (!file_exists($filePath)) {
            return null;
        }

        // Fetch and decode the JSON file
        $countriesList = json_decode(file_get_contents($filePath), true);

        if (!$country) {
            return null;
        }

        // Find the country based on the country code
        $country = collect($countriesList)->firstWhere('country_name_english', $country);

        if($country){
            return $country;
        } else {
            return null;
        }
    }
}
