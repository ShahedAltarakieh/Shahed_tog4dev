<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('item_id'); // Reference to the item
            $table->string('subscription_id'); // A unique ID for subscription
            $table->string('payment_id')->nullable();; // Reference to the payment
            $table->string('model_type'); // Reference to the payment
            $table->decimal('price', 10, 2);
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable(); // Optional, for fixed-term subscriptions
            $table->string('status')->default('active'); // active, expired, or canceled
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
