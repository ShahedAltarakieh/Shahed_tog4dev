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
        Schema::table('quick_contribution', function($table) {
            $table->integer('target')->nullable()->after('description_en');
            $table->integer('target_usd')->nullable()->after('target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quick_contribution', function (Blueprint $table) {
            //
        });
    }
};
