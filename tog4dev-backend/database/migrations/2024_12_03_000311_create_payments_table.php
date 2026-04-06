<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();  // Automatically creates an auto-incrementing 'id' column
            $table->unsignedBigInteger('user_id');  // To store the user ID, assuming it references users table
            $table->string('cart_id');  // Cart ID from your array
            $table->string('status');  // Response status (e.g., 'A')
            $table->decimal('amount', 10, 2);
            $table->string('contract_id')->nullable();
            $table->string('payment_type');  // Payment type (e.g., 'Credit Card', 'PayPal', etc.)
            $table->string('acquirer_message')->nullable();  // Acquirer message, can be null
            $table->string('acquirer_rrn')->nullable();  // Acquirer RRN (Receipt Reference Number), can be null
            $table->string('resp_code')->nullable();  // Response code (e.g., 'G83803')
            $table->string('resp_message')->nullable();  // Response message (e.g., 'Authorised')
            $table->string('signature')->nullable();  // Signature (for verification)
            $table->string('token')->nullable();  // Token, can be null
            $table->string('tran_ref')->nullable();  // Transaction reference number (e.g., 'TST2433702170312')
            $table->timestamps();  // Created at and Updated at timestamps

            // Foreign key relationship to users table
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

