<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->text('text_ar')->nullable()->after('text');
            $table->string('short_text_ar')->nullable()->after('short_text');
            $table->string('cta_text_ar')->nullable()->after('cta_text');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['title_ar', 'text_ar', 'short_text_ar', 'cta_text_ar']);
        });
    }
};
