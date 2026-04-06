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
        Schema::create('excel_sheet_orders', function (Blueprint $table) {
            $table->id();
            $table->string('t4d_reference')->nullable();  // T4D Reference
            $table->string('order_id')->nullable();  // Order ID
            $table->string('created_order_at')->nullable();  // Created At
            $table->string('payment_status')->nullable();  // Payment Status (could be an enum depending on your use case)
            $table->string('total')->nullable();  // Total (with 2 decimal places)
            $table->string('name')->nullable();  // Name
            $table->string('customer_email')->nullable();  // Customer Email
            $table->string('customer_phone_number')->nullable();  // Customer Phone Number
            $table->text('customer_address')->nullable();  // Customer Address
            $table->text('order_items')->nullable();  // Order Items (This can be JSON or a serialized string, depending on your needs)
            $table->string('payment_method')->nullable();  // Payment Method,
            $table->integer("excel_sheet_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_sheet_orders');
    }
};
