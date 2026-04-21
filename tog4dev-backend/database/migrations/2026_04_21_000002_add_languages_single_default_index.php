<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // DB-level guarantee: at most one languages row may have is_default = true.
        // Postgres partial unique index; on other drivers fall back to model-level enforcement.
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS languages_single_default_idx ON languages ((1)) WHERE is_default = true');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS languages_single_default_idx');
        }
    }
};
