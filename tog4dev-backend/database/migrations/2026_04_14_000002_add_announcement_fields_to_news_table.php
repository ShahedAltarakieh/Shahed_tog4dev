<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'announcement_visibility')) {
                $table->string('announcement_visibility')->default('news_only')->after('position');
            }
            if (!Schema::hasColumn('news', 'announcement_text')) {
                $table->string('announcement_text')->nullable()->after('announcement_visibility');
            }
            if (!Schema::hasColumn('news', 'announcement_cta')) {
                $table->string('announcement_cta')->nullable()->after('announcement_text');
            }
            if (!Schema::hasColumn('news', 'announcement_badge')) {
                $table->string('announcement_badge')->default('NEW')->after('announcement_cta');
            }
            if (!Schema::hasColumn('news', 'announcement_start')) {
                $table->timestamp('announcement_start')->nullable()->after('announcement_badge');
            }
            if (!Schema::hasColumn('news', 'announcement_end')) {
                $table->timestamp('announcement_end')->nullable()->after('announcement_start');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn([
                'announcement_visibility',
                'announcement_text',
                'announcement_cta',
                'announcement_badge',
                'announcement_start',
                'announcement_end',
            ]);
        });
    }
};
