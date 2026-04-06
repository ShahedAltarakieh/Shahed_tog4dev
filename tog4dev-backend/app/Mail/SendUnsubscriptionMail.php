<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SendUnsubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $cancellation_date;

    public function __construct($subscription)
    {
        $this->subscription = $subscription;
        $this->cancellation_date = Carbon::now()->toDateString();
    }

    public function build()
    {
        $locale = $this->subscription->payment->lang;

        // Determine the email view based on locale
        $template = $locale === 'ar' ? 'emails.unSubscription' : 'emails.unSubscription_en';

        $data = [];
        
        $data = [
            "title" => $this->subscription->title,
            "title_en" => $this->subscription->title_en,
        ];

        $data["name"] = $this->subscription->user->first_name." ".$this->subscription->user->last_name;
        $data["cancellation_date"] = $this->cancellation_date;

        // Start building the email
        $email = $this->subject('Subscription Cancellation Confirmation')
            ->view($template)
            ->with($data);

        return $email;
    }
}
