<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateMysqlToNeon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrate-mysql-to-neon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from local MySQL database to Neon PostgreSQL database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database migration from local MySQL to Neon PostgreSQL...');

        // Test connections
        try {
            $mysqlDb = DB::connection('mysql')->getDatabaseName();
            $pgsqlDb = DB::connection('pgsql')->getDatabaseName();
            $this->info("Source MySQL Database: {$mysqlDb}");
            $this->info("Destination Neon Database: {$pgsqlDb}");
        } catch (\Exception $e) {
            $this->error('Database connection failed: ' . $e->getMessage());
            return 1;
        }

        if ($this->confirm('Do you want to TRUNCATE (clear) the destination tables on Neon PostgreSQL before importing from MySQL?', false)) {
            $this->info('Truncating destination tables on Neon PostgreSQL...');
            try {
                DB::connection('pgsql')->statement('TRUNCATE TABLE detail_surat_jalan, surat_jalan, projects, master_barang, users CASCADE');
                $this->info('Destination tables truncated successfully.');
            } catch (\Exception $e) {
                $this->error('Failed to truncate tables: ' . $e->getMessage());
                return 1;
            }
        } else {
            $this->info('Existing data on Neon will be kept. New records will be inserted (ignoring ID conflicts).');
        }

        $tables = [
            'users',
            'master_barang',
            'projects',
            'surat_jalan',
            'detail_surat_jalan'
        ];

        foreach ($tables as $table) {
            $this->info("\nMigrating table: {$table}...");
            $count = 0;

            DB::connection('mysql')->table($table)->orderBy('id')->chunk(500, function ($records) use ($table, &$count) {
                $data = [];
                foreach ($records as $record) {
                    $data[] = (array) $record;
                }

                if (!empty($data)) {
                    DB::connection('pgsql')->table($table)->insertOrIgnore($data);
                    $count += count($data);
                    $this->output->write(".");
                }
            });

            $this->output->writeln("");
            $this->info("Finished {$table}: {$count} records migrated.");

            // Reset PostgreSQL auto-increment sequence
            $this->resetPostgresSequence($table);
        }

        $this->info("\n🎉 Database migration from MySQL to Neon PostgreSQL completed successfully!");
        return 0;
    }

    /**
     * Reset PostgreSQL sequence after inserting explicit IDs.
     */
    protected function resetPostgresSequence($table)
    {
        try {
            DB::connection('pgsql')->statement("SELECT setval(pg_get_serial_sequence('{$table}', 'id'), COALESCE((SELECT MAX(id) FROM {$table}), 1), true)");
            $this->line("Synced auto-increment sequence for {$table}.");
        } catch (\Exception $e) {
            $this->warn("Could not sync sequence for {$table}: " . $e->getMessage());
        }
    }
}
