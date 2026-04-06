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
        Schema::create('deleted_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('cart_id')->nullable();
            $table->string('status')->nullable();
            $table->decimal('amount', 15, 3)->nullable();
            $table->unsignedBigInteger('referrer_id')->nullable();
            $table->unsignedBigInteger('collection_team_id')->nullable();
            $table->string('contract_id')->nullable();
            $table->string('temp_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('acquirer_message')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->string('acquirer_rrn')->nullable();
            $table->string('resp_code')->nullable();
            $table->string('resp_message')->nullable();
            $table->text('signature')->nullable();
            $table->text('token')->nullable();
            $table->string('tran_ref')->nullable();
            $table->string('lang')->nullable();
            $table->boolean('send_email')->default(false);
            $table->string('odoo_column_to_payments')->nullable();
            $table->longText('response')->nullable();
            $table->string('country')->nullable();
            $table->string('cliq_number')->nullable();
            $table->string('name_on_card')->nullable();
            $table->string('bank_issuer')->nullable();
            $table->boolean('not_send_email')->default(false);
            $table->timestamps();
            $table->integer('odoo_id')->nullable();
            $table->string('source')->nullable();
            $table->boolean('need_sync')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deleted_payments');
    }
};
