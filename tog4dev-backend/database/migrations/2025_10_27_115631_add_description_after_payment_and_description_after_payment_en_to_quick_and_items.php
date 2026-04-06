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
        Schema::table('items', function (Blueprint $table) {
            $table->longText('description_after_payment')->nullable()->after('beneficiaries_message');
            $table->longText('description_after_payment_en')->nullable()->after('description_after_payment');
        });
        Schema::table('quick_contribution', function (Blueprint $table) {
            $table->longText('description_after_payment')->nullable()->after('beneficiaries_message');
            $table->longText('description_after_payment_en')->nullable()->after('description_after_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
