<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_dedication', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('cart_id'); // Foreign key for cart
            $table->string('name'); // Dedication name
            $table->softDeletes(); // Adds deleted_at column for soft deletes
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_dedication');
    }
};
