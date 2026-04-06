<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\Cart;

class SendUnsubscriptionMail2 extends Mailable
{
    use Queueable, SerializesModels;

    public $quicks;
    public $user;
    public $lang;
    public $cancellation_date;

    public function __construct($quicks, $user, $lang)
    {
        $this->quicks = $quicks;
        $this->user = $user;
        $this->lang = $lang;
        $this->cancellation_date = Carbon::now()->toDateString();
    }

    public function build()
    {
        $locale = $this->lang;

        // Determine the email view based on locale
        $template = $locale === 'ar' ? 'emails.unSubscription_2' : 'emails.unSubscription_en_2';
        
        $beneficiaries_msg_ar = $this->quicks->beneficiaries_message;
        $beneficiaries_msg_en = $this->quicks->beneficiaries_message_en;

        $beneficiaries_msg = $locale === 'ar' ? $beneficiaries_msg_ar : $beneficiaries_msg_en;

        $data = [
            "title" => $this->quicks->title,
            "title_en" => $this->quicks->title_en,
            "beneficiaries_msg" => $beneficiaries_msg
        ];

        $data["name"] = $this->user->first_name." ".$this->user->last_name;
        $data["cancellation_date"] = $this->cancellation_date;
        $title = $locale == "ar" ? "تم إلغاء اشتراكك بنجاح" : "Subscription Cancellation Confirmation";
        // Start building the email
        $email = $this->subject($title)
            ->view($template)
            ->with($data);

        return $email;
    }
}
