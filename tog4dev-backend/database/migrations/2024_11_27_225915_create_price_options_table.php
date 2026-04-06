<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('price_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Links to `items` table
            $table->string('d1_option');
            $table->string('d1_option_en');
            $table->string('d2_option')->nullable();
            $table->string('d2_option_en')->nullable();
            $table->integer('price');
            $table->integer('price_en'); // Price in English currency
            $table->boolean('is_default')->default(false); // To mark the default option
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('price_options');
    }
}
