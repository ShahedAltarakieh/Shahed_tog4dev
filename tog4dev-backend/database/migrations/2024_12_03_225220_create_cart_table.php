<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id')->nullable(); // Reference to the user
            $table->unsignedBigInteger('item_id')->nullable(); // Reference to the item
            $table->string('model_type'); // Reference to the payment
            $table->string('payment_id')->nullable(); // Reference to the payment
            $table->decimal('price', 10, 2); // Price of the item
            $table->string('type'); // Type of the cart entry
            $table->boolean('is_paid')->default(false); // Payment status
            $table->integer('quantity')->default(0);
            $table->string('temp_id')->nullable();
            $table->integer('option_id')->nullable();
            $table->timestamps(); // created_at and updated_at columns

            // Add foreign keys if necessary (optional)
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart');
    }
}
