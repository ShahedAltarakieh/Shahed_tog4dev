<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FetarCampaignMail extends Mailable
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
        return $this->from('info@tog4dev.com', 'Together for Intermediation Services (Together for Development)')->subject('معاً للتنمية | خدمة زكاة الفطرة الى غزة')->view("emails.campaigns_templates.fetar_campaign_2");
    }
}
