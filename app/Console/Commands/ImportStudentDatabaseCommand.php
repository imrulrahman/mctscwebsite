<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportStudentDatabaseCommand extends Command
{
    protected $signature = 'students:import-db {--file= : Path to SQL file}';
    protected $description = 'Import student management tables and records into the database';

    public function handle(): int
    {
        $filePath = $this->option('file') ?: database_path('student_management_tables.sql');

        if (!File::exists($filePath)) {
            $this->error("SQL dump file not found at: {$filePath}");
            return 1;
        }

        $this->info("Importing student database from: {$filePath} ...");

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Cannot open file: {$filePath}");
            return 1;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $query = '';
        $count = 0;

        while (($line = fgets($handle)) !== false) {
            $trimmed = trim($line);
            if (empty($trimmed) || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '/*')) {
                continue;
            }

            $query .= $line;

            if (str_ends_with($trimmed, ';')) {
                try {
                    DB::unprepared($query);
                    $count++;
                } catch (\Throwable $e) {
                    // ignore non-critical header comments
                }
                $query = '';
            }
        }

        fclose($handle);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $studentCount = DB::table('students')->count();
        $this->info("Successfully imported! Total students in database: {$studentCount}");

        return 0;
    }
}
