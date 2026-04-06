<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $user;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        $locale = app()->getLocale();

        // Determine the email view based on locale
        $newUserEmail = $locale === 'ar' ? 'emails.password' : 'emails.password_en';

        return $this->subject('Your Collection Team Account Password')
                    ->view($newUserEmail)
                    ->with(['password' => $this->password, 'user' => $this->user]);
    }
}