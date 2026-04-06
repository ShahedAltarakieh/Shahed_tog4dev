<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('efwateercom_services')) {
            return;
        }

        if ($this->parentIdColumnStoresStrings()) {
            return;
        }

        try {
            Schema::table('efwateercom_services', function (Blueprint $table) {
                $table->dropForeign(['parent_id']);
            });
        } catch (\Throwable $e) {
            //
        }

        Schema::table('efwateercom_services', function (Blueprint $table) {
            $table->string('parent_id_new')->nullable();
        });

        DB::table('efwateercom_services')->select(['id', 'parent_id'])->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                DB::table('efwateercom_services')
                    ->where('id', $row->id)
                    ->update([
                        'parent_id_new' => $row->parent_id !== null ? (string) $row->parent_id : null,
                    ]);
            }
        });

        Schema::table('efwateercom_services', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });

        Schema::table('efwateercom_services', function (Blueprint $table) {
            $table->string('parent_id')->nullable();
        });

        DB::table('efwateercom_services')->select(['id', 'parent_id_new'])->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                DB::table('efwateercom_services')
                    ->where('id', $row->id)
                    ->update([
                        'parent_id' => $row->parent_id_new,
                    ]);
            }
        });

        Schema::table('efwateercom_services', function (Blueprint $table) {
            $table->dropColumn('parent_id_new');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }

    protected function parentIdColumnStoresStrings(): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            $columns = $connection->select('PRAGMA table_info(efwateercom_services)');
            foreach ($columns as $col) {
                if ($col->name === 'parent_id') {
                    $type = strtolower((string) $col->type);

                    return str_contains($type, 'char')
                        || str_contains($type, 'text')
                        || str_contains($type, 'varchar');
                }
            }

            return false;
        }

        if ($driver === 'pgsql') {
            $row = $connection->selectOne(
                "select data_type from information_schema.columns where table_schema = 'public' and table_name = 'efwateercom_services' and column_name = 'parent_id'"
            );

            if (! $row) {
                return false;
            }

            $type = strtolower((string) ($row->data_type ?? ''));

            return in_array($type, ['character varying', 'varchar', 'char', 'text'], true);
        }

        $database = $connection->getDatabaseName();
        $row = $connection->selectOne(
            'select DATA_TYPE from information_schema.COLUMNS where TABLE_SCHEMA = ? and TABLE_NAME = ? and COLUMN_NAME = ?',
            [$database, 'efwateercom_services', 'parent_id']
        );

        if (! $row) {
            return false;
        }

        $type = strtolower((string) ($row->DATA_TYPE ?? ''));

        return in_array($type, ['varchar', 'char', 'text'], true);
    }
};
