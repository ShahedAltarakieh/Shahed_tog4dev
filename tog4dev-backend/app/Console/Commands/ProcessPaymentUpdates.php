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
use App\Models\ExcelOrders;
use App\Mail\PaymentReceiptMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Services\OdooService;
use App\Jobs\OdooJobs\SendPaymentToOdooJob;

class ProcessPaymentUpdates extends Command
{
    protected $signature = 'app:process-send-prof-emails';
    protected $description = 'Process payment to send contract and invoice via email';

    public function handle()
    {

        $lock = Cache::lock('payment-proccess-lock', 300); // Lock for 5 minutes
        if ($lock->get()) {
            try {
                // Fetch payments with status 'initiated'
                $payments = Payment::where('status', 'approved')
                    ->where('send_email', 0)
                    ->where('not_send_email', 0)
                    ->where('cart_id', '<>', 'N12281773707117')
                    ->where('cart_id', '<>', 'N92561773799913')
                    ->where('cart_id', '<>', 'N95211773886929')
                    ->where('payment_type', '<>', 'Efawateercom')
                    ->take(2)
                    ->get();
                $this->processPayment($payments);
                // $payments = Payment::where('id', '15525')->get();
                // $this->processPaymentCliQ($payments);
                // $payments = Payment::where('status', 'approved')->where('send_email', 0)->where('not_send_email', 1)->take(1000)->get();
                // $this->processPaymentCliQ($payments);
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

                $lastContract = Payment::whereNotNull('contract_id')
                    ->max('contract_id');

                if ($lastContract) {
                    // Extract the numeric part and increment it
                    $number = (int) Str::after($lastContract, 'W-');
                    $newContractId = 'W-' . str_pad($number + 1, 5, '0', STR_PAD_LEFT);
                } else {
                    // If no contract exists, start with W-00001
                    $newContractId = 'W-00001';
                }

                if($payment->contract_id == null){
                    $payment->update([
                        'contract_id' => $newContractId,
                    ]);
                } else {
                    $newContractId = $payment->contract_id;
                }

                $carts = Cart::where('payment_id', $payment->id)
                    ->where('is_paid', 1)
                    ->with('model', 'dedications') // Load related model and dedications for contract
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

                Storage::makeDirectory("cliq/12/".$payment->tran_ref);
                
                $cart_id_for_name = str_replace('/', '-', $payment->cart_id) . ".pdf";
                $tran_ref_for_name = str_replace('/', '-', $payment->tran_ref);
                $invoicePath = storage_path('app/invoices/invoice_' . $cart_id_for_name);
                $invoiceCliq = storage_path('app/cliq/12/'.$tran_ref_for_name.'/invoice-' . $newContractId . '.pdf');
                $pdf->save($invoicePath);
                $pdf->save($invoiceCliq);

                $payment->addMedia($invoicePath)
                    ->toMediaCollection('invoices');

                // Generate and save contract PDF
                $contractPDF = PDF::loadView($emailContract, ['payment' => $payment, 'carts' => $carts, 'country' => $full_country], [], [
                    'default_font' => '"Readex Pro", sans-serif',
                ]);

                $contractPath = storage_path('app/contracts/contract_' . $cart_id_for_name);
                $contractCliq = storage_path('app/cliq/12/'.$tran_ref_for_name.'/contract-' . $newContractId . '.pdf');
                $contractPDF->save($contractPath);
                $contractPDF->save($contractCliq);

                $payment->addMedia($contractPath)
                    ->toMediaCollection('contracts');

                if($subscription){
                    // Full path to the source file
                    $source = base_path('resources/views/emails/privacy_policy.pdf');

                    // Destination path in the storage/app folder
                    $destination = storage_path('app/cliq/12/'.$tran_ref_for_name.'/'.$privacy_policy_name);

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
    
    public function processPayment($payments){
        foreach ($payments as $payment) {
            try {
                $tran_ref_id = $payment->tran_ref;
                $excel_order = ExcelOrders::where("order_id", $tran_ref_id)->first();

                $subscription = 0;
                
                $user = $payment->userDetails;

                $full_country = $this->getTranslatedCountry($user->country);

                $lastContract = Payment::whereNotNull('contract_id')
                    ->max('contract_id');

                if ($lastContract) {
                    // Extract the numeric part and increment it
                    $number = (int) Str::after($lastContract, 'W-');
                    $newContractId = 'W-' . str_pad($number + 1, 5, '0', STR_PAD_LEFT);
                } else {
                    // If no contract exists, start with W-00001
                    $newContractId = 'W-00001';
                }

                if($payment->contract_id == null){
                    $payment->update([
                        'contract_id' => $newContractId,
                    ]);
                } else {
                    $newContractId = $payment->contract_id;
                }

                $carts = Cart::where('payment_id', $payment->id)
                    ->where('is_paid', 1)
                    ->with('model', 'dedications') // Load related model and dedications for contract
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
                } else {
                    $emailInvoice = 'emails.invoice_en';
                    $emailContract = 'emails.contract_en';
                }

                // Generate and save invoice PDF
                $pdf = PDF::loadView($emailInvoice, ['payment' => $payment, 'user' => $user, 'carts' => $carts, 'numberToWords' => 'numberToWords'], [], [
                    'format' => 'A4-L',
                ]);

                $cart_id_for_name = str_replace('/', '-', $payment->cart_id) . ".pdf";
                $invoicePath = storage_path('app/invoices/invoice_' . $cart_id_for_name);
                $pdf->save($invoicePath);

                $payment->addMedia($invoicePath)
                    ->toMediaCollection('invoices');

                // Generate and save contract PDF
                $contractPDF = PDF::loadView($emailContract, ['payment' => $payment, 'carts' => $carts, 'country' => $full_country], [], [
                    'default_font' => '"Readex Pro", sans-serif',
                ]);

                $contractPath = storage_path('app/contracts/contract_' . $cart_id_for_name);
                $contractPDF->save($contractPath);

                $payment->addMedia($contractPath)
                    ->toMediaCollection('contracts');

                if($payment->user->email == "invoice.tog4dev2025@gmail.com"){
                    $cc_email = env('INFO_EMAIL');
                } else {
                    $cc_email = env('BILLS_EMAIL');
                }
                // Send email with all carts; on failure send to cc_email as fallback
                $mail = new PaymentReceiptMail($payment, $user, $carts, $payment->getMedia('invoices')->last(), $payment->getMedia('contracts')->last(), $subscription);
                try {
                    Mail::to($payment->user->email)->cc($cc_email)->send($mail);
                } catch (\Exception $e) {
                    $this->error("Failed to send payment receipt to {$payment->user->email} for payment {$payment->cart_id}, sending to cc: " . $e->getMessage());
                    Mail::to($cc_email)->send($mail);
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
                    'retry_fetch_response' => 1
                ]);
                $user = User::find($payment->user_id);
                if($user->odoo_id == null){
                    $response = app(OdooService::class)->post('v1/addPartner', $user->toOdoo(true));
                    if (isset($response['result']['status']) && $response['result']['status'] === 200 && isset($response['result']['data']['id'])) {
                        $odooId = $response['result']['data']['id'];
                        $user->odoo_id = $odooId;
                        $user->saveQuietly(); // prevents triggering updated observer
                    }
                } else {
                    app(OdooService::class)->put("v1/updatePartner", $user->toOdoo(false));
                }
                SendPaymentToOdooJob::dispatch($payment->id)->delay(2);
                sleep(40);

            } catch (\Exception $e) {
                $this->error("Error processing payment {$payment->cart_id}: " . $e->getMessage());
            }
        }
    }

    public function inquery($cart_id)
    {
        $profileId = config('paytabs.profile_id');
        $apiUrl = 'https://secure-jordan.paytabs.com/payment/query';
        $authorizationKey = config('paytabs.server_key');

        $payload = [
            'profile_id'       => $profileId,
            'cart_id'          => $cart_id
        ];

        // Call the PayTabs API
        $response = Http::withHeaders([
            'Authorization' => $authorizationKey,
            'Content-Type'  => 'application/json',
        ])->post($apiUrl, $payload);

        if ($response->successful()) {
            $data = json_decode($response->body(), 1);
            if(isset($data[0])){
                if (isset($data[0]["payment_result"]['response_status'])) {
                    $country_code = $data[0]["customer_details"]["country"];
                    $country = $this->getCountry($country_code);
                    if(!empty($country)){
                        return $country;
                    }
                }
            }
        }
    }

    public function getCountry($countryCode)
    {
        // Path to the JSON file in the public folder
        $filePath = public_path('countries.json');

        // Check if the file exists
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Fetch and decode the JSON file
        $countriesList = json_decode(file_get_contents($filePath), true);

        if (!$countryCode) {
            return response()->json(['error' => 'Country code not provided'], 400);
        }

        // Find the country based on the country code
        $country = collect($countriesList)->firstWhere('country_code', $countryCode);

        if ($country) {
            return $country['country_name_english'];
        }

        return null;
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
