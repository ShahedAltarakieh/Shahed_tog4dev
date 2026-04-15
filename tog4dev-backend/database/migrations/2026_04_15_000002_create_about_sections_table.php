<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('about_page_id');
            $table->string('section_key');
            $table->string('title')->nullable();
            $table->string('title_en')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->text('body')->nullable();
            $table->text('body_en')->nullable();
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_text_en')->nullable();
            $table->string('cta_link')->nullable();
            $table->string('cta_link_en')->nullable();
            $table->string('layout')->nullable();
            $table->json('settings')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->foreign('about_page_id')->references('id')->on('about_pages')->onDelete('cascade');
            $table->index(['about_page_id', 'section_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_sections');
    }
};
