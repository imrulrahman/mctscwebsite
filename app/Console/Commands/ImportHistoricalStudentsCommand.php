<?php

namespace App\Console\Commands;

use App\Services\StudentImportService;
use Illuminate\Console\Command;

class ImportHistoricalStudentsCommand extends Command
{
    protected $signature = 'students:import-historical {file?}';
    protected $description = 'Import historical student database from Excel/CSV';

    public function handle(): int
    {
        $filePath = $this->argument('file');
        if (empty($filePath)) {
            $defaultCsv = 'C:\\Users\\mahmu\\Downloads\\Student DB MCTSC 2018 - Present 2025(StudentDB).csv';
            $defaultXlsx = 'C:\\Users\\mahmu\\Downloads\\Student DB MCTSC 2018 - Present 2025 (2).xlsx';

            if (file_exists($defaultCsv)) {
                $filePath = $defaultCsv;
            } elseif (file_exists($defaultXlsx)) {
                $filePath = $defaultXlsx;
            }
        }

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return self::FAILURE;
        }

        $this->info("Parsing file: {$filePath}...");
        $rows = StudentImportService::parseFile($filePath);
        $this->info("Found " . count($rows) . " student records. Starting import...");

        $batch = StudentImportService::executeImport($rows, basename($filePath));

        $this->info("Import completed!");
        $this->line("Total: {$batch->total_rows}");
        $this->line("Successful: {$batch->successful_rows}");
        $this->line("Duplicates skipped: {$batch->duplicate_rows}");
        $this->line("Errors: {$batch->error_rows}");

        return self::SUCCESS;
    }
}
