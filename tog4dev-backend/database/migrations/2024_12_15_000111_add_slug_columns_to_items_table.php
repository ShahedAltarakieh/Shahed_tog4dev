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
        Schema::table('items', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('status'); // Adding slug column after 'status'
            $table->string('slug_en')->nullable()->after('slug'); // Adding slug_en column after 'slug'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['slug', 'slug_en']); // Dropping slug columns
        });
    }
};
