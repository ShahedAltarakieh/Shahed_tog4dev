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
        Schema::table('cart_dedication', function (Blueprint $table) {
            $table->unsignedTinyInteger('dedication_phrase_id')->nullable()->after('name');
            $table->string('dedication_phrase_ar', 255)->nullable()->after('dedication_phrase_id');
            $table->string('dedication_phrase_en', 255)->nullable()->after('dedication_phrase_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_dedication', function (Blueprint $table) {
            $table->dropColumn(['dedication_phrase_id', 'dedication_phrase_ar', 'dedication_phrase_en']);
        });
    }
};
