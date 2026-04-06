<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ErrorEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $subscription;
    public $renew;
    /**
     * Create a new message instance.
     */
    public function __construct($payment = null, $subscription = null, $renew = false)
    {
        $this->payment = $payment;
        $this->subscription = $subscription;
        $this->renew = $renew;
    }

    public function build()
    {
        // Determine the email view based on locale
        $newUserEmail = 'emails.error_payment';
        $subject = "Error in payment";
        if($this->renew){
            $subject .= " - Renew";
        }
        return $this->subject($subject)
                    ->view($newUserEmail)
                    ->with(['payment' => $this->payment, "subscription" => $this->subscription]);
    }
}
