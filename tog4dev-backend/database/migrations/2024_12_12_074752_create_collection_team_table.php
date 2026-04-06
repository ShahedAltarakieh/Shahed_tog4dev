<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionTeamTable extends Migration
{
    public function up()
    {
        Schema::create('collection_team', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('country');
            $table->string('city');
            $table->string('address');
            $table->timestamps();
            $table->softDeletes();  // Soft delete column
        });
    }

    public function down()
    {
        Schema::dropIfExists('collection_team');
    }
}
