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
        Schema::table('quick_contribution', function (Blueprint $table) {
            $table->longText('beneficiaries_message')->nullable()->after('description');
            $table->longText('beneficiaries_message_en')->nullable()->after('beneficiaries_message');
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
