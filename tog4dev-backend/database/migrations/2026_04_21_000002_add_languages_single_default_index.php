<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS languages_single_default_idx ON languages ((1)) WHERE is_default = true');
            return;
        }

        if ($driver === 'mysql') {
            // Generated virtual column that is 1 only when this row is the
            // default; NULL otherwise. A unique index over it enforces at
            // most one default at the DB level (MySQL allows many NULLs).
            if (!Schema::hasColumn('languages', 'default_singleton')) {
                DB::statement('ALTER TABLE languages ADD COLUMN default_singleton TINYINT GENERATED ALWAYS AS (CASE WHEN is_default = 1 THEN 1 ELSE NULL END) VIRTUAL');
            }
            DB::statement('CREATE UNIQUE INDEX languages_single_default_idx ON languages (default_singleton)');
            return;
        }

        if ($driver === 'sqlite') {
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS languages_single_default_idx ON languages (is_default) WHERE is_default = 1');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql' || $driver === 'sqlite') {
            DB::statement('DROP INDEX IF EXISTS languages_single_default_idx');
            return;
        }

        if ($driver === 'mysql') {
            DB::statement('DROP INDEX languages_single_default_idx ON languages');
            if (Schema::hasColumn('languages', 'default_singleton')) {
                DB::statement('ALTER TABLE languages DROP COLUMN default_singleton');
            }
        }
    }
};
