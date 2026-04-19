<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->string('label_en')->nullable();
            $table->string('label_ar')->nullable();
            $table->boolean('visible')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        $defaults = [
            ['page_key' => 'news_gallery',  'label_en' => 'News & Gallery', 'label_ar' => 'الأخبار والمعرض', 'order' => 1],
            ['page_key' => 'about_us',      'label_en' => 'About Us',       'label_ar' => 'من نحن',         'order' => 2],
            ['page_key' => 'contact_us',    'label_en' => 'Contact Us',     'label_ar' => 'اتصل بنا',        'order' => 3],
            ['page_key' => 'crowdfunding',  'label_en' => 'Crowdfunding',   'label_ar' => 'التمويل الجماعي', 'order' => 4],
            ['page_key' => 'ngoverse',      'label_en' => 'NGOverse',       'label_ar' => 'NGOverse',       'order' => 5],
            ['page_key' => 'projects',      'label_en' => 'Projects',       'label_ar' => 'المشاريع',        'order' => 6],
            ['page_key' => 'organizations', 'label_en' => 'Organizations',  'label_ar' => 'المؤسسات',        'order' => 7],
        ];
        foreach ($defaults as $row) {
            DB::table('nav_settings')->insert(array_merge($row, [
                'visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_settings');
    }
};
