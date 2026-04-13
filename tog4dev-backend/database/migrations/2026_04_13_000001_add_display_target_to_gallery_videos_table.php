<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_videos', function (Blueprint $table) {
            $table->string('display_target', 20)->default('both')->after('thumbnail_url');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_videos', function (Blueprint $table) {
            $table->dropColumn('display_target');
        });
    }
};
