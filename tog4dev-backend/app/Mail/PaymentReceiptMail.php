<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $country;
    public $user;
    public $carts; // Collection of carts
    public $invoicePath;
    public $contractPath;
    public $subscription;

    public function __construct($payment, $user, $carts, $invoicePath = null, $contractPath = null, $subscription = 0)
    {
        $this->user = $user;
        $this->carts = $carts;
        $this->invoicePath = $invoicePath;
        $this->contractPath = $contractPath;
        $this->subscription = $subscription;
        $this->payment = $payment;
    }

    public function build()
    {
        $locale = app()->getLocale();

        $this->country = $this->getCountry($this->user->country);

        if($this->payment->subscription_id != null){
            // Determine the email view based on locale
            $payment_receipt = $locale === 'ar' ? 'emails.payment_receipt_renew' : 'emails.payment_receipt_renew_en';
            $subject = $locale === 'ar' ? 'تجديد الاشتراك الشهري' : 'Renew monthly subscription';
        } else {
            // Determine the email view based on locale
            $payment_receipt = $locale === 'ar' ? 'emails.payment_receipt' : 'emails.payment_receipt_en';
            $subject = "Your Payment Receipt and Contract";
        }

        $subject = $this->payment->contract_id." - ".$subject;

        // Start building the email
        $email = $this->subject($subject)
            ->view($payment_receipt); // The email view

        if($locale === 'ar'){
            $contract_name = "Arabic_Contract.pdf";
            $invoice_name = "Arabic_Invoice.pdf";
            $privacy_policy_name = "سياسات-الإشتراكات.pdf";
        } else{
            $contract_name = "English_Contract.pdf";
            $invoice_name = "English_Invoice.pdf";
            $privacy_policy_name = "Subscriptions-Policy.pdf";
        }
        // Attach invoice if it exists
        if (!empty($this->invoicePath)) {
            $email->attach($this->invoicePath, [
                'as' => $invoice_name,
                'mime' => 'application/pdf',
            ]);
        }

        // Attach contract
        $email->attach($this->contractPath, [
            'as' => $contract_name,
            'mime' => 'application/pdf',
        ]);
        if ($this->subscription ==1) {
            $email->attach(base_path('resources/views/emails/privacy_policy.pdf'), [
                'as' => $privacy_policy_name,
                'mime' => 'application/pdf',
            ]);
        }
        
        return $email;
    }

    public function getCountry($country)
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
