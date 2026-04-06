<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CollectionTeamMail extends Mailable
{
    use Queueable, SerializesModels;

    public $carts;

    public function __construct($carts)
    {
        $this->carts = $carts;
    }

    public function build()
    {
        $locale = app()->getLocale();

        // Determine the email view based on locale
        $collection_team = $locale === 'ar' ? 'emails.collection_team' : 'emails.collection_team_en';

        // Start building the email
        $email = $this->subject('Your Delegation Makes a Difference - Thank You!')
            ->view($collection_team); // The email view

        return $email;
    }

}
