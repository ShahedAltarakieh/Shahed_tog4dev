<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdhaCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from('info@tog4dev.com', 'Together for Intermediation Services (Together for Development)')->subject('اضحيتك لغزة رغم الحصار')->view("emails.campaigns_templates.adha_campaign");
    }
}
