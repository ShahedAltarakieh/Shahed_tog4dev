<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApologyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;

    }

    public function build()
    {
        $locale = app()->getLocale();

        // Determine the email view based on locale
        $newUserEmail = $locale === 'ar' ? 'emails.apology' : 'emails.apology_en';
        $subject = $locale === 'ar' ? 'نعتذر على الخطأ' : 'Apologies for Error';
        return $this->subject($subject)
                    ->view($newUserEmail)
                    ->with(['user' => $this->user]);
    }
}
