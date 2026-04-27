<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_maintenance', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->string('label_en');
            $table->string('label_ar');
            $table->boolean('is_under_update')->default(false);
            $table->text('message_en')->nullable();
            $table->text('message_ar')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        $now = now();
        $pages = [
            ['home', 'Home', 'الرئيسية'],
            ['individual-projects', 'Individual Projects', 'المشاريع الفردية'],
            ['organizations-projects', 'Organizations Projects', 'مشاريع المنظمات'],
            ['crowdfunding', 'Crowdfunding', 'التمويل الجماعي'],
            ['ngoverse', 'NGOverse', 'عالم المنظمات'],
            ['mama-giving-shope', "Mama' Giving Shope", 'درب العطاء لأمي'],
            ['about-us', 'About Us', 'من نحن'],
            ['contact-us', 'Contact Us', 'تواصل معنا'],
            ['news', 'News', 'الأخبار'],
            ['photos', 'Photos', 'الصور'],
            ['videos', 'Videos', 'الفيديو'],
            ['basket', 'Basket', 'السلة'],
            ['subscriptions', 'Subscriptions', 'الاشتراكات'],
            ['login', 'Login', 'تسجيل الدخول'],
            ['signup', 'Sign Up', 'إنشاء حساب'],
            ['terms-and-conditions', 'Terms & Conditions', 'الشروط والأحكام'],
            ['privacy-policy', 'Privacy Policy', 'سياسة الخصوصية'],
            ['refund-policy', 'Refund Policy', 'سياسة الإرجاع'],
            ['subscription-policy', 'Subscription Policy', 'سياسة الاشتراكات'],
            ['cookie-policy', 'Cookie Policy', 'سياسة ملفات تعريف الارتباط'],
        ];

        $rows = [];
        foreach ($pages as $i => [$key, $en, $ar]) {
            $rows[] = [
                'page_key'        => $key,
                'label_en'        => $en,
                'label_ar'        => $ar,
                'is_under_update' => false,
                'message_en'      => null,
                'message_ar'      => null,
                'order'           => $i + 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }
        DB::table('page_maintenance')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('page_maintenance');
    }
};
