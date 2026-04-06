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
            DB::statement('ALTER TABLE efwateercom_inquiries MODIFY service_type VARCHAR(255) NULL');

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE efwateercom_inquiries ALTER COLUMN service_type DROP NOT NULL');
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
            DB::statement('ALTER TABLE efwateercom_inquiries MODIFY service_type VARCHAR(255) NOT NULL');

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE efwateercom_inquiries ALTER COLUMN service_type SET NOT NULL');
        }
    }
};
