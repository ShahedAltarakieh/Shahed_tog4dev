<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('efwateercom_inquiries')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE efwateercom_inquiries MODIFY efwateercom_service_id BIGINT UNSIGNED NULL');

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE efwateercom_inquiries ALTER COLUMN efwateercom_service_id DROP NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('efwateercom_inquiries')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE efwateercom_inquiries MODIFY efwateercom_service_id BIGINT UNSIGNED NOT NULL');

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE efwateercom_inquiries ALTER COLUMN efwateercom_service_id SET NOT NULL');
        }
    }
};
