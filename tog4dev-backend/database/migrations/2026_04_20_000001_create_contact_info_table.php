<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_info', function (Blueprint $table) {
            $table->id();

            // Bilingual short address (used on the small info card)
            $table->string('city_country')->nullable();             // EN: "Amman, Jordan"
            $table->string('city_country_ar')->nullable();          // AR
            $table->string('street_short')->nullable();             // EN: "University of Jordan Street"
            $table->string('street_short_ar')->nullable();          // AR

            // Office card (Visit Our Office)
            $table->string('company_name')->nullable();             // EN
            $table->string('company_name_ar')->nullable();          // AR
            $table->string('address_line1')->nullable();            // EN: "First Floor, Office 103 - Hayat"
            $table->string('address_line1_ar')->nullable();         // AR
            $table->text('address_line2')->nullable();              // EN: full street address
            $table->text('address_line2_ar')->nullable();           // AR

            // Working hours (free-text, bilingual)
            $table->string('working_hours')->nullable();            // EN: "Sun – Thu · 9am – 5pm"
            $table->string('working_hours_ar')->nullable();         // AR

            // Bilingual short subtitles for the 4 quick info cards
            $table->string('phone_sub')->nullable();
            $table->string('phone_sub_ar')->nullable();
            $table->string('landline_sub')->nullable();
            $table->string('landline_sub_ar')->nullable();
            $table->string('email_sub')->nullable();
            $table->string('email_sub_ar')->nullable();

            // Phones / WhatsApp / Emails
            $table->string('phone_primary')->nullable();            // mobile
            $table->string('whatsapp_number')->nullable();          // WhatsApp (digits only, no +)
            $table->string('landline')->nullable();
            $table->string('email_primary')->nullable();

            // Optional extra entries (JSON array of strings)
            $table->json('extra_phones')->nullable();
            $table->json('extra_emails')->nullable();

            // Social media (single object: { facebook, instagram, snapchat, twitter, linkedin, youtube, tiktok })
            $table->json('social_links')->nullable();

            // Map
            $table->string('map_link')->nullable();                 // share link (goo.gl/...)
            $table->text('map_embed_url')->nullable();              // src for the iframe
            $table->decimal('map_lat', 10, 7)->nullable();
            $table->decimal('map_lng', 10, 7)->nullable();

            $table->timestamps();
        });

        // Seed the singleton row with the values currently hard-coded on the page
        DB::table('contact_info')->insert([
            'id' => 1,
            'city_country'        => 'Amman, Jordan',
            'city_country_ar'     => 'عمّان، الأردن',
            'street_short'        => 'University of Jordan Street',
            'street_short_ar'     => 'شارع الجامعة الأردنية',
            'company_name'        => 'Together for Intermediation Services LLC (Together for Development)',
            'company_name_ar'     => 'شركة معاً لخدمات الوساطة ذ.م.م (معاً للتنمية)',
            'address_line1'       => 'First Floor, Office 103 - Hayat',
            'address_line1_ar'    => 'الطابق الأول، مكتب 103 - حياة',
            'address_line2'       => 'FM Building, behind Al-Dustour Newspaper - University of Jordan Street, Amman, Jordan.',
            'address_line2_ar'    => 'مبنى FM، خلف جريدة الدستور - شارع الجامعة الأردنية، عمّان، الأردن.',
            'working_hours'       => 'Sun – Thu · 9am – 5pm',
            'working_hours_ar'    => 'الأحد - الخميس · 9 صباحًا - 5 مساءً',
            'phone_sub'           => 'Mobile & WhatsApp',
            'phone_sub_ar'        => 'موبايل وواتساب',
            'landline_sub'        => 'Sun – Thu · 9am – 5pm',
            'landline_sub_ar'     => 'الأحد - الخميس · 9 صباحًا - 5 مساءً',
            'email_sub'           => 'We reply within 24 hours',
            'email_sub_ar'        => 'نرد خلال 24 ساعة',
            'phone_primary'       => '+962779400900',
            'whatsapp_number'     => '962779400900',
            'landline'            => '+96264020202',
            'email_primary'       => 'info@tog4dev.com',
            'extra_phones'        => json_encode([]),
            'extra_emails'        => json_encode([]),
            'social_links'        => json_encode([
                'facebook'  => 'https://www.facebook.com/share/h4FZ2p8TsLWE2vKy/?mibextid=LQQJ4d',
                'instagram' => 'https://www.instagram.com/tog4dev/profilecard/?igsh=MmEyNnFhZDR2cDY4',
                'snapchat'  => 'https://snapchat.com/t/kwXP9FSM',
                'twitter'   => 'https://x.com/Tog4Dev?t=YxqzngaJv1WOAeS1241qMw&s=09',
                'linkedin'  => '',
                'youtube'   => '',
                'tiktok'    => '',
            ]),
            'map_link'            => 'https://maps.app.goo.gl/5rUnmTWCu17rQvGZ6',
            'map_embed_url'       => 'https://www.google.com/maps?q=31.9976776,35.8801005&z=17&output=embed',
            'map_lat'             => 31.9976776,
            'map_lng'             => 35.8801005,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_info');
    }
};
