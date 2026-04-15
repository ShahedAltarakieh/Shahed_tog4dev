<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_page_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('about_page_id');
            $table->integer('version');
            $table->json('snapshot');
            $table->string('action')->default('publish');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('about_page_id')->references('id')->on('about_pages')->onDelete('cascade');
            $table->index(['about_page_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_page_versions');
    }
};
