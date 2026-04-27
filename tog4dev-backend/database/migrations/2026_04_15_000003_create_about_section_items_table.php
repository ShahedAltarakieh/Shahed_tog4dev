<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_section_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('about_section_id');
            $table->string('title')->nullable();
            $table->string('title_en')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('link')->nullable();
            $table->string('link_en')->nullable();
            $table->string('value')->nullable();
            $table->string('label')->nullable();
            $table->string('label_en')->nullable();
            $table->json('social_links')->nullable();
            $table->json('extra')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->foreign('about_section_id')->references('id')->on('about_sections')->onDelete('cascade');
            $table->index('about_section_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_section_items');
    }
};
