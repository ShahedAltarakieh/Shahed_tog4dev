<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReminderSubscriptionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    public function build()
    {
        $locale = $this->subscription->payment->lang;
        $email_template = $locale === 'ar' ? 'emails.reminder_email_subscription' : 'emails.reminder_email_subscription_en';
        $subject = $locale == "ar" ? "رسالة تذكير للاشتراك" : "Subscription Reminder";

        $data = [
            "title" => $this->subscription->title,
            "title_en" => $this->subscription->title_en,
            "location" => $this->subscription->location,
            "location_en" => $this->subscription->location_en,
            "full_name" => $this->subscription->user->first_name." ".$this->subscription->user->last_name
        ];

        return $this->subject($subject)
            ->view($email_template)
            ->with([
                'subscription' => $this->subscription,
                'data' => $data
        ]);
    }
}
