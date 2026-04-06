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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_en');
            $table->longText('description')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('location');
            $table->string('location_en');
            $table->integer('category_id');
            $table->double('amount')->default(1);
            $table->double('amount_usd')->default(1);
            $table->integer('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
