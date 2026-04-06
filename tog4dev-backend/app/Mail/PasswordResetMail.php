<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($resetLink, $user)
    {
        $this->resetLink = $resetLink;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $locale = app()->getLocale();

        $resetPasswordEmail = $locale === 'ar' ? 'emails.reset_password' : 'emails.reset_password_en';

        return $this->subject('Password Reset Request')
            ->view($resetPasswordEmail)
            ->with([
                'resetLink' => $this->resetLink,
                'user' => $this->user,
            ]);
    }
}
