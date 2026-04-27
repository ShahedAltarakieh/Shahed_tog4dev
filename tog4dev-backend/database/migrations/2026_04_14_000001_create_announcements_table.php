<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('text');
            $table->string('short_text')->nullable();
            $table->string('link')->nullable();
            $table->string('cta_text')->nullable();
            $table->enum('source_type', ['manual', 'news', 'system'])->default('manual');
            $table->unsignedBigInteger('news_id')->nullable();
            $table->enum('badge_type', ['LIVE', 'INFO', 'ALERT', 'NEW'])->default('INFO');
            $table->enum('target_view', ['desktop', 'mobile', 'both'])->default('both');
            $table->boolean('is_active')->default(true);
            $table->integer('order_no')->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();

            $table->foreign('news_id')->references('id')->on('news')->onDelete('set null');
            $table->index(['is_active', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
