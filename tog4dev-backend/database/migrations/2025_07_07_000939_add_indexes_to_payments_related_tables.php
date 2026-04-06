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
        Schema::table('payments', function (Blueprint $table) {
            $table->index('status');
            $table->index('cart_id');
            $table->index('name_on_card');
            $table->index('bank_issuer');
            $table->index('amount');
            $table->index('user_id');
            $table->index('referrer_id');
            $table->index('created_at');
        });

        Schema::table('payment_user_details', function (Blueprint $table) {
            $table->index('first_name');
            $table->index('last_name');
            $table->index('email');
            $table->index('phone');
        });

        Schema::table('influencers', function (Blueprint $table) {
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['status', 'cart_id', 'name_on_card', 'bank_issuer', 'amount', 'user_id', 'referrer_id', 'created_at']);
        });

        Schema::table('payment_user_details', function (Blueprint $table) {
            $table->dropIndex(['first_name', 'last_name', 'email', 'phone']);
        });

        Schema::table('influencers', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};
