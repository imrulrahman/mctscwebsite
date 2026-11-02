<?php

namespace App\Services;

use App\Models\Occupation;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Models\StudentImportBatch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentImportService
{
    /**
     * Clean string from invisible characters, non-breaking spaces, and normalize UTF-8
     */
    public static function clean(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        // Replace NBSP with normal space
        $str = str_replace(["\xc2\xa0", "\xa0", "&nbsp;"], ' ', $value);
        // Remove non-printable control characters except newline and tab
        $str = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $str);
        // Ensure valid UTF-8
        $str = @iconv('UTF-8', 'UTF-8//IGNORE', $str) ?: $str;
        return trim($str);
    }

    /**
     * Parse uploaded file (.xlsx or .csv) into array of rows
     */
    public static function parseFile(string $filePath): array
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($ext === 'xlsx' || $ext === 'xls') {
            return self::parseXlsxWithPython($filePath);
        }

        return self::parseCsv($filePath);
    }

    /**
     * Parse CSV file
     */
    public static function parseCsv(string $filePath): array
    {
        $rows = [];
        if (!file_exists($filePath)) {
            return $rows;
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return $rows;
        }

        $headerFound = false;
        $rowIdx = 0;

        while (($data = fgetcsv($handle, 10000, ',')) !== false) {
            $rowIdx++;
            if (!$headerFound) {
                // Check if this row is the header row
                foreach ($data as $col) {
                    $lower = strtolower(trim($col));
                    if ($lower === 'id no' || $lower === 'student id' || $lower === 'student name') {
                        $headerFound = true;
                        break;
                    }
                }
                continue;
            }

            // Must have student ID or student name
            if (count($data) > 2 && (!empty(trim($data[1] ?? '')) || !empty(trim($data[2] ?? '')))) {
                $rows[] = $data;
            }
        }

        fclose($handle);
        return $rows;
    }

    /**
     * Parse XLSX using Python helper
     */
    public static function parseXlsxWithPython(string $filePath): array
    {
        $csvTemp = tempnam(sys_get_temp_dir(), 'mctsc_import_') . '.csv';
        $pyScript = base_path('convert_xlsx_sheet.py');

        if (!file_exists($pyScript)) {
            $code = <<<'PY'
import sys, openpyxl, csv

xlsx_path = sys.argv[1]
out_csv = sys.argv[2]

wb = openpyxl.load_workbook(xlsx_path, read_only=True, data_only=True)
sheet_name = 'StudentDB' if 'StudentDB' in wb.sheetnames else wb.sheetnames[0]
ws = wb[sheet_name]

with open(out_csv, 'w', encoding='utf-8', newline='') as f:
    writer = csv.writer(f)
    for row in ws.iter_rows(values_only=True):
        writer.writerow([str(c) if c is not None else '' for c in row])
PY;
            file_put_contents($pyScript, $code);
        }

        $cmd = 'python ' . escapeshellarg($pyScript) . ' ' . escapeshellarg($filePath) . ' ' . escapeshellarg($csvTemp);
        shell_exec($cmd);

        if (file_exists($csvTemp)) {
            $rows = self::parseCsv($csvTemp);
            @unlink($csvTemp);
            return $rows;
        }

        return [];
    }

    /**
     * Map raw row into normalized Student data array
     */
    public static function mapRowToStudentData(array $row, ?int $createdBy = null): ?array
    {
        $studentId = self::clean($row[1] ?? '');
        $studentName = self::clean($row[2] ?? '');

        if (empty($studentId) && empty($studentName)) {
            return null;
        }

        $gender = self::clean($row[3] ?? '');
        $genderNorm = match (strtolower($gender)) {
            'm', 'male' => 'Male',
            'f', 'female' => 'Female',
            default => !empty($gender) ? ucfirst(strtolower($gender)) : null,
        };

        $admissionDate = self::parseDate(self::clean($row[4] ?? ''));
        $courseType = self::clean($row[5] ?? 'Short Course');
        $section = self::clean($row[6] ?? '');
        $occupation = self::clean($row[7] ?? '');
        $rplCourse = self::clean($row[8] ?? '');
        $academicYear = self::clean($row[9] ?? '');
        $session = self::clean($row[10] ?? '');
        $batchNo = self::clean($row[11] ?? '');
        $shift = self::clean($row[12] ?? '');
        $donor = self::clean($row[13] ?? '');
        $phone = self::clean($row[14] ?? '');
        $dob = self::parseDate(self::clean($row[15] ?? ''));
        $ageRaw = self::clean($row[16] ?? '');
        $age = is_numeric($ageRaw) ? (int)$ageRaw : null;
        $nidBirthCert = self::clean($row[17] ?? '');
        $parentPhone = self::clean($row[18] ?? '');
        $eduQual = self::clean($row[19] ?? '');
        $fatherName = self::clean($row[20] ?? '');
        $motherName = self::clean($row[21] ?? '');
        $siblings = self::clean($row[22] ?? '');
        $maritalStatus = self::clean($row[23] ?? '');
        $address = self::clean($row[24] ?? '');
        $paymentStatus = self::clean($row[25] ?? '');

        // Training Status
        $rawStatus = strtolower(self::clean($row[26] ?? ''));
        $trainingStatus = match ($rawStatus) {
            'complite', 'completed' => 'Completed',
            'running' => 'Running',
            'dropped' => 'Dropped',
            default => 'Active',
        };

        // Marks
        $vivaRaw = self::clean($row[27] ?? '');
        $theoryRaw = self::clean($row[28] ?? '');
        $practicalRaw = self::clean($row[29] ?? '');
        $totalRaw = self::clean($row[30] ?? '');

        $viva = is_numeric($vivaRaw) ? (float)$vivaRaw : null;
        $theory = is_numeric($theoryRaw) ? (float)$theoryRaw : null;
        $practical = is_numeric($practicalRaw) ? (float)$practicalRaw : null;
        $totalMarks = is_numeric($totalRaw) ? (float)$totalRaw : null;
        $grade = self::clean($row[31] ?? '');
        $nsdaBteb = self::clean($row[32] ?? '');

        // Employment
        $rawJobStatus = strtolower(self::clean($row[33] ?? ''));
        $jobStatus = match ($rawJobStatus) {
            'employed', 'yes', 'engaged in job' => 'Employed',
            'self employed', 'self defended' => 'Self Employed',
            'not yet emplyed', 'not yet employed', 'no' => 'Not Yet Employed',
            default => (!empty(self::clean($row[34] ?? ''))) ? 'Employed' : 'Not Yet Employed',
        };

        $companyName = self::clean($row[34] ?? '');
        $companyType = self::clean($row[35] ?? '');
        $employerPhone = self::clean($row[36] ?? '');
        $companyAddress = self::clean($row[37] ?? '');
        $designation = self::clean($row[38] ?? '');
        $employmentType = self::clean($row[39] ?? '');
        $joiningDate = self::parseDate(self::clean($row[40] ?? ''));
        $salaryRaw = self::clean($row[41] ?? '');
        $salary = is_numeric($salaryRaw) ? (float)$salaryRaw : null;

        $remarks = self::clean($row[42] ?? '') ?: self::clean($row[46] ?? '');

        // Ethnic & PWD & Religion
        $ethnicRaw = strtolower(self::clean($row[43] ?? 'no'));
        $ethnic = in_array($ethnicRaw, ['yes', '1', 'true']) ? 'Yes' : 'No';

        $pwdRaw = strtolower(self::clean($row[44] ?? 'no'));
        $pwd = in_array($pwdRaw, ['yes', '1', 'true']) ? 'Yes' : 'No';

        $religion = self::clean($row[45] ?? '');

        // Session number extraction
        $sessionNumber = 1;
        if (preg_match('/(\d)/', $session, $sMatches)) {
            $sessionNumber = (int)$sMatches[1];
        }

        // Auto-generate ID if missing
        if (empty($studentId)) {
            $studentId = StudentIdService::generateId($occupation, $academicYear ?: date('Y'), $session, $section);
        }

        $occupationCode = StudentIdService::resolveOccupationCode($occupation, $section);

        // Certificate status
        $certStatus = ($trainingStatus === 'Completed' || ($totalMarks !== null && $totalMarks > 0)) ? 'Issued' : 'Pending';

        return [
            'student_id' => $studentId,
            'student_name' => $studentName,
            'gender' => $genderNorm,
            'date_of_admission' => $admissionDate,
            'course_type' => $courseType ?: 'Short Course',
            'section' => $section,
            'occupation' => $occupation,
            'occupation_code' => $occupationCode,
            'rpl_course' => $rplCourse,
            'academic_year' => $academicYear ?: date('Y'),
            'session' => $session ?: 'Session 1',
            'session_number' => $sessionNumber,
            'batch_no' => $batchNo,
            'shift' => $shift,
            'donor' => $donor,
            'phone' => $phone,
            'dob' => $dob,
            'age' => $age,
            'nid_birth_cert' => $nidBirthCert,
            'parent_phone' => $parentPhone,
            'educational_qualification' => $eduQual,
            'father_name' => $fatherName,
            'mother_name' => $motherName,
            'siblings' => $siblings,
            'marital_status' => $maritalStatus,
            'address' => $address,
            'present_address' => $address,
            'religion' => $religion,
            'ethnic' => $ethnic,
            'pwd' => $pwd,
            'payment_status' => $paymentStatus,
            'training_status' => $trainingStatus,
            'viva_mark' => $viva,
            'theory_mark' => $theory,
            'practical_mark' => $practical,
            'total_marks' => $totalMarks,
            'grade' => $grade,
            'nsda_bteb_result' => $nsdaBteb,
            'certificate_status' => $certStatus,
            'job_status' => $jobStatus,
            'company_name' => $companyName,
            'company_type' => $companyType,
            'employer_phone' => $employerPhone,
            'company_address' => $companyAddress,
            'designation' => $designation,
            'employment_type' => $employmentType,
            'joining_date' => $joiningDate,
            'salary' => $salary,
            'remarks' => $remarks,
            'created_by' => $createdBy,
        ];
    }

    /**
     * Preview analysis of rows before importing
     */
    public static function previewAnalysis(array $rawRows): array
    {
        $totalRows = count($rawRows);
        $previewRows = [];
        $duplicateIds = [];
        $errors = [];
        $existingIds = Student::pluck('student_id')->flip()->toArray();

        $seenInFile = [];

        foreach ($rawRows as $idx => $r) {
            $sid = self::clean($r[1] ?? '');
            $sname = self::clean($r[2] ?? '');

            if (empty($sid) && empty($sname)) {
                $errors[] = "Row " . ($idx + 1) . ": Empty Student ID and Name.";
                continue;
            }

            if (!empty($sid)) {
                if (isset($existingIds[$sid])) {
                    $duplicateIds[] = $sid;
                }
                if (isset($seenInFile[$sid])) {
                    $duplicateIds[] = $sid . ' (repeated in file)';
                }
                $seenInFile[$sid] = true;
            }

            if (count($previewRows) < 15) {
                $mapped = self::mapRowToStudentData($r);
                if ($mapped) {
                    $previewRows[] = $mapped;
                }
            }
        }

        return [
            'total_rows' => $totalRows,
            'duplicate_count' => count(array_unique($duplicateIds)),
            'duplicate_samples' => array_slice(array_unique($duplicateIds), 0, 10),
            'error_count' => count($errors),
            'error_samples' => array_slice($errors, 0, 5),
            'preview_rows' => $previewRows,
        ];
    }

    /**
     * Execute full import with transaction and batch logging
     */
    public static function executeImport(array $rawRows, string $fileName, ?int $userId = null): StudentImportBatch
    {
        $batch = StudentImportBatch::create([
            'file_name' => $fileName,
            'imported_by' => $userId,
            'total_rows' => count($rawRows),
            'status' => 'processing',
        ]);

        $success = 0;
        $duplicates = 0;
        $errors = 0;
        $errorLogs = [];

        $existingIds = Student::pluck('student_id')->flip()->toArray();
        $chunks = array_chunk($rawRows, 150);

        foreach ($chunks as $chunk) {
            DB::beginTransaction();
            try {
                $insertData = [];
                $batchesToRecord = [];

                foreach ($chunk as $row) {
                    $mapped = self::mapRowToStudentData($row, $userId);
                    if (!$mapped) {
                        $errors++;
                        continue;
                    }

                    $sid = $mapped['student_id'];
                    if (isset($existingIds[$sid])) {
                        $duplicates++;
                        continue;
                    }

                    $existingIds[$sid] = true;
                    $insertData[] = array_merge($mapped, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if (!empty($mapped['batch_no'])) {
                        $batchesToRecord[$mapped['batch_no']] = [
                            'name' => $mapped['batch_no'],
                            'academic_year' => $mapped['academic_year'],
                        ];
                    }
                }

                if (!empty($insertData)) {
                    Student::insert($insertData);
                    $success += count($insertData);
                }

                // Upsert batches
                foreach ($batchesToRecord as $b) {
                    if (!empty($b['name'])) {
                        StudentBatch::firstOrCreate(
                            ['name' => $b['name'], 'academic_year' => $b['academic_year']],
                            ['status' => 'active']
                        );
                    }
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $errors += count($chunk);
                $cleanMsg = self::clean($e->getMessage());
                $errorLogs[] = substr($cleanMsg, 0, 500);
                Log::error('Student import chunk failed: ' . $cleanMsg);
            }
        }

        $cleanErrLog = !empty($errorLogs) ? substr(implode("\n", array_unique($errorLogs)), 0, 5000) : null;

        $batch->update([
            'successful_rows' => $success,
            'duplicate_rows' => $duplicates,
            'error_rows' => $errors,
            'status' => $errors > 0 && $success === 0 ? 'failed' : 'completed',
            'error_log' => $cleanErrLog,
        ]);

        return $batch;
    }

    /**
     * Safe date parser handling multiple formats (1-Jan-18, 2018-01-01, 01/01/2018)
     */
    protected static function parseDate(?string $value): ?string
    {
        if (empty($value) || $value === '0000-00-00' || $value === 'NULL' || $value === '!DOB?') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
