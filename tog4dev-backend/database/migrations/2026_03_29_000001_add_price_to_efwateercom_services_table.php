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
        Schema::table('efwateercom_services', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('efwateercom_services', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
