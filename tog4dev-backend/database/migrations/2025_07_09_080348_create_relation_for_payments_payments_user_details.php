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
        Schema::table('payment_user_details', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_id')->unique()->change();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relation_for_payments_payments_user_details');
    }
};
