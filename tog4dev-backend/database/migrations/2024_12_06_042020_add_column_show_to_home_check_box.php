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
        Schema::table('testimonials', function($table) {
            $table->integer('show_in_home')->default(0)->after('status');
        });
        Schema::table('stories', function($table) {
            $table->integer('show_in_home')->default(0)->after('status');
        });
        Schema::table('partners', function($table) {
            $table->integer('show_in_home')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
