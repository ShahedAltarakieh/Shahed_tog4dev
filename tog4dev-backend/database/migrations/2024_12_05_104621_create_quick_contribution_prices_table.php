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
        Schema::create('quick_contribution_prices', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('quick_contribution_id'); // Foreign Key
            $table->decimal('price', 10, 2)->nullable();; // Price Field
            $table->decimal('price_usd', 10, 2)->nullable();; // Price Field
            $table->timestamps(); // Created At & Updated At

            // Foreign Key Constraint
            $table->foreign('quick_contribution_id')->references('id')->on('quick_contribution')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_contribution_prices');
    }
};
