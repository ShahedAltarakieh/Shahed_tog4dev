<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_en');
            $table->longText('description');
            $table->longText('description_en');
            $table->integer('type');
            $table->integer('status');
            $table->string('hero_title');
            $table->string('hero_title_en');
            $table->longText('hero_description');
            $table->longText('hero_description_en');
            $table->integer('all_option');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
