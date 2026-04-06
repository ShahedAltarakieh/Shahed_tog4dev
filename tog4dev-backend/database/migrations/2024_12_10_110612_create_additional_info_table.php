<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalInfoTable extends Migration
{
    public function up()
    {
        Schema::create('additional_info', function (Blueprint $table) {
            $table->id(); // auto-incrementing ID
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // Foreign key to 'items' table
            $table->text('project_story')->nullable();
            $table->text('project_story_en')->nullable();
            $table->text('bold_description')->nullable();
            $table->text('bold_description_en')->nullable();
            $table->text('normal_description')->nullable();
            $table->text('normal_description_en')->nullable();
            $table->softDeletes(); // This will add the 'deleted_at' column for soft deletes
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('additional_info');
    }
}
