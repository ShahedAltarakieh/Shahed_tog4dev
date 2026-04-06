<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_prices', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('item_id'); // Foreign Key
            $table->decimal('price', 10, 2)->nullable();; // Price Field
            $table->decimal('price_en', 10, 2)->nullable();; // Price Field
            $table->timestamps(); // Created At & Updated At

            // Foreign Key Constraint
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_prices');
    }
}
