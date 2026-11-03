<?php

namespace App\Services;

use App\Models\Occupation;
use App\Models\Student;
use App\Models\StudentBatch;
use App\Models\StudentImportBatch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use XMLReader;
use ZipArchive;

class StudentImportService
{
    /**
     * Parse uploaded file (XLSX, XLSM, XLS, CSV, TXT)
     * Pure PHP - no external Python or shell dependency.
     */
    public static function parseFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if (in_array($ext, ['xlsx', 'xlsm', 'xls']) || self::isZipFile($filePath)) {
            try {
                $rows = self::parseXlsxPurePhp($filePath);
                if (!empty($rows)) {
                    return $rows;
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('parseXlsxPurePhp failed, falling back to CSV: ' . $e->getMessage());
            }
        }

        $csvRows = self::parseCsv($filePath);
        return self::autoTransposeIfVertical($csvRows);
    }

    /**
     * Check if file starts with ZIP magic bytes (PK..)
     */
    protected static function isZipFile(string $filePath): bool
    {
        $f = @fopen($filePath, 'rb');
        if (!$f) return false;
        $bytes = fread($f, 4);
        fclose($f);
        return $bytes === "PK\x03\x04" || $bytes === "PK\x05\x06";
    }

    /**
     * Pure PHP XLSX/XLSM Parser using ZipArchive and XMLReader
     */
    public static function parseXlsxPurePhp(string $filePath): array
    {
        if (!file_exists($filePath) || !class_exists('ZipArchive')) {
            return [];
        }

        $zip = new ZipArchive();
        $res = $zip->open($filePath);
        if ($res !== true) {
            return [];
        }

        // 1. Read shared strings
        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml !== false) {
            $xml = @simplexml_load_string($sharedStringsXml);
            if ($xml !== false) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string)$si->t;
                    } elseif (isset($si->r)) {
                        $text = '';
                        foreach ($si->r as $r) {
                            $text .= (string)$r->t;
                        }
                        $sharedStrings[] = $text;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Collect candidate worksheet files in priority order
        $candidateSheetFiles = [];

        $workbookXml = $zip->getFromName('xl/workbook.xml');
        $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');

        if ($workbookXml !== false && $relsXml !== false) {
            $wb = @simplexml_load_string($workbookXml);
            $rels = @simplexml_load_string($relsXml);
            $relMap = [];
            if ($rels) {
                foreach ($rels->Relationship as $rel) {
                    $relMap[(string)$rel['Id']] = (string)$rel['Target'];
                }
            }

            if ($wb && isset($wb->sheets)) {
                $priority = [];
                $other = [];
                foreach ($wb->sheets->sheet as $s) {
                    $name = strtolower(trim((string)$s['name']));
                    $rId = (string)$s->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'];
                    if (empty($rId) && isset($s->attributes('r', true)['id'])) {
                        $rId = (string)$s->attributes('r', true)['id'];
                    }

                    if ($rId && isset($relMap[$rId])) {
                        $target = $relMap[$rId];
                        $targetFile = str_starts_with($target, 'worksheets/') ? 'xl/' . $target : (str_starts_with($target, '/') ? ltrim($target, '/') : 'xl/' . ltrim($target, '/'));
                        if (str_contains($name, 'stdnt') || str_contains($name, 'student') || str_contains($name, 'database') || str_contains($name, 'db')) {
                            $priority[] = $targetFile;
                        } else {
                            $other[] = $targetFile;
                        }
                    }
                }
                $candidateSheetFiles = array_merge($priority, $other);
            }
        }

        // Add all worksheets found in the zip as fallback candidates
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entryName = $zip->getNameIndex($i);
            if (str_starts_with($entryName, 'xl/worksheets/') && str_ends_with($entryName, '.xml')) {
                if (!in_array($entryName, $candidateSheetFiles)) {
                    $candidateSheetFiles[] = $entryName;
                }
            }
        }

        // 3. Test candidate sheets and pick the one with most rows / valid data
        $bestRows = [];
        $bestScore = -1;

        foreach ($candidateSheetFiles as $sheetFile) {
            $sheetXml = $zip->getFromName($sheetFile);
            if ($sheetXml === false) {
                continue;
            }

            $sheetRows = self::parseSheetXmlContent($sheetXml, $sharedStrings);
            if (empty($sheetRows)) {
                continue;
            }

            $score = count($sheetRows);

            // Check if this sheet has student database headers
            $hIdx = -1;
            $hMap = self::buildHeaderMap($sheetRows, $hIdx);
            if ($hIdx >= 0 && count($hMap) >= 3) {
                $score += 100000; // Overwhelming bonus for sheets with authentic student headers
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRows = $sheetRows;
            }
        }

        $zip->close();
        return self::autoTransposeIfVertical($bestRows);
    }

    /**
     * Automatically detect and transpose vertical / column-wise student sheets
     */
    public static function autoTransposeIfVertical(array $rows): array
    {
        if (count($rows) < 3) {
            return $rows;
        }

        // 1. If it already has standard horizontal student headers, do not transpose
        $hIdx = -1;
        $hMap = self::buildHeaderMap($rows, $hIdx);
        if ($hIdx >= 0 && count($hMap) >= 3) {
            return $rows;
        }

        // 2. Check if Column 0 contains known vertical field names
        $verticalMatches = 0;
        $anchors = [
            'id', 'idno', 'studentid', 'roll', 'mrno', 'mr',
            'name', 'studentname', 'fullname', 'traineename', 'nameofstudent',
            'father', 'fathersname', 'fathername', 'guardian',
            'mother', 'mothersname', 'mothername',
            'gender', 'sex',
            'dob', 'dateofbirth', 'birthdate', 'age',
            'occupation', 'trade', 'course', 'coursetype',
            'phone', 'mobile', 'contact', 'contactno', 'contactnoparents', 'parentphone',
            'address', 'presentaddress', 'permanentaddress',
            'siblings', 'familyinformation', 'religion', 'ethnic', 'pwd', 'grade'
        ];

        foreach ($rows as $row) {
            $col0 = preg_replace('/[^a-z0-9]/', '', strtolower(trim((string)($row[0] ?? ''))));
            if (!empty($col0) && in_array($col0, $anchors)) {
                $verticalMatches++;
            }
        }

        // If 3 or more rows in Column 0 match field names, it's a transposed sheet!
        if ($verticalMatches >= 3) {
            $maxCol = 0;
            foreach ($rows as $r) {
                $maxCol = max($maxCol, count($r));
            }

            $transposed = [];
            for ($c = 0; $c < $maxCol; $c++) {
                $newRow = [];
                for ($r = 0; $r < count($rows); $r++) {
                    $newRow[] = $rows[$r][$c] ?? '';
                }
                $transposed[] = $newRow;
            }

            return $transposed;
        }

        return $rows;
    }

    /**
     * Parse XMLReader stream of worksheet XML
     */
    public static function parseSheetXmlContent(string $sheetXml, array $sharedStrings): array
    {
        $rows = [];
        $reader = new XMLReader();
        if (!@$reader->xml($sheetXml)) {
            return [];
        }

        $currentRow = [];
        $currentCellRef = '';
        $cellType = '';
        $cellVal = '';

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::ELEMENT) {
                if ($reader->name === 'row') {
                    $currentRow = [];
                } elseif ($reader->name === 'c') {
                    $currentCellRef = $reader->getAttribute('r') ?? '';
                    $cellType = $reader->getAttribute('t') ?? '';
                    $cellVal = '';
                } elseif ($reader->name === 'v' || $reader->name === 't') {
                    $cellVal = $reader->readString();
                }
            } elseif ($reader->nodeType === XMLReader::END_ELEMENT) {
                if ($reader->name === 'c') {
                    $finalVal = '';
                    if ($cellType === 's') {
                        $idx = (int)$cellVal;
                        $finalVal = $sharedStrings[$idx] ?? '';
                    } else {
                        $finalVal = $cellVal;
                    }

                    // Convert cell ref (e.g. "B5") to 0-based column index
                    $colLetters = preg_replace('/[0-9]/', '', $currentCellRef);
                    $colIdx = 0;
                    for ($k = 0; $k < strlen($colLetters); $k++) {
                        $colIdx = $colIdx * 26 + (ord(strtoupper($colLetters[$k])) - ord('A') + 1);
                    }
                    $colIdx = max(0, $colIdx - 1);

                    $currentRow[$colIdx] = $finalVal;
                } elseif ($reader->name === 'row') {
                    if (!empty($currentRow)) {
                        $maxCol = max(array_keys($currentRow));
                        $rowDense = [];
                        for ($c = 0; $c <= $maxCol; $c++) {
                            $rowDense[$c] = $currentRow[$c] ?? '';
                        }
                        $rows[] = $rowDense;
                    }
                }
            }
        }
        $reader->close();

        return $rows;
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

        while (($data = fgetcsv($handle, 10000, ',')) !== false) {
            $rows[] = $data;
        }
        fclose($handle);

        return $rows;
    }

    /**
     * Build dynamic header map from rows
     */
    public static function buildHeaderMap(array $rows, int &$headerRowIndex): array
    {
        $headerMap = [];
        $headerRowIndex = -1;

        foreach ($rows as $rIdx => $row) {
            $foundAnchor = false;
            foreach ($row as $colText) {
                $clean = strtolower(trim((string)$colText));
                if (in_array($clean, ['id no', 'student id', 'student name', 'trainee name', 'name', 'occupation', 'trade', 'mr no', 'mr', 'registration no', 'reg no', 'roll no'])) {
                    $foundAnchor = true;
                    break;
                }
            }

            if ($foundAnchor) {
                $headerRowIndex = $rIdx;
                foreach ($row as $cIdx => $headerVal) {
                    $norm = preg_replace('/[^a-z0-9]/', '', strtolower((string)$headerVal));
                    if (!empty($norm) && !isset($headerMap[$norm])) {
                        $headerMap[$norm] = $cIdx;
                    }
                }
                break;
            }
        }

        return $headerMap;
    }

    /**
     * Helper to get value from row using multiple column aliases
     */
    public static function getFieldVal(array $row, array $headerMap, array $aliases, $default = ''): string
    {
        foreach ($aliases as $alias) {
            $norm = preg_replace('/[^a-z0-9]/', '', strtolower($alias));
            if (isset($headerMap[$norm])) {
                $colIdx = $headerMap[$norm];
                if (isset($row[$colIdx])) {
                    $val = self::clean((string)$row[$colIdx]);
                    if ($val !== '') {
                        return $val;
                    }
                }
            }
        }

        return (string)$default;
    }

    /**
     * Preview analysis before import
     */
    public static function previewAnalysis(array $rawRows): array
    {
        $headerRowIdx = -1;
        $headerMap = self::buildHeaderMap($rawRows, $headerRowIdx);

        $dataRows = ($headerRowIdx >= 0) ? array_slice($rawRows, $headerRowIdx + 1) : $rawRows;

        $totalRows = count($dataRows);
        $previewSample = [];
        $duplicateSamples = [];
        $existingCount = 0;
        $newCount = 0;
        $errorCount = 0;
        $existingIds = [];
        try {
            $existingIds = Student::pluck('student_id')->filter()->flip()->toArray();
        } catch (\Throwable $e) {
            $existingIds = [];
        }

        foreach ($dataRows as $idx => $row) {
            try {
                $mapped = self::mapRowToStudentData($row, $headerMap);
                if ($mapped === null) {
                    $errorCount++;
                    continue;
                }

                $isDuplicate = !empty($mapped['student_id']) && isset($existingIds[$mapped['student_id']]);
                if ($isDuplicate) {
                    $existingCount++;
                    if (count($duplicateSamples) < 5) {
                        $duplicateSamples[] = $mapped['student_id'];
                    }
                } else {
                    $newCount++;
                }

                if (count($previewSample) < 15) {
                    $mapped['is_duplicate'] = $isDuplicate;
                    $previewSample[] = $mapped;
                }
            } catch (\Throwable $e) {
                $errorCount++;
            }
        }

        return [
            'total_rows' => $totalRows,
            'duplicate_count' => $existingCount,
            'duplicate_records' => $existingCount,
            'new_records' => $newCount,
            'error_count' => $errorCount,
            'duplicate_samples' => $duplicateSamples,
            'preview_rows' => $previewSample,
            'sample_data' => $previewSample,
        ];
    }

    /**
     * Map a single row to student data array
     */
    public static function mapRowToStudentData(array $row, array $headerMap = [], ?int $createdBy = null): ?array
    {
        try {
            // 1. Student ID
        $studentId = self::getFieldVal($row, $headerMap, ['idno', 'studentid', 'id', 'rollno', 'traineeid', 'mrno', 'mr', 'regno', 'registrationno', 'slno', 'sl']);
        // 2. Student Name
        $studentName = self::getFieldVal($row, $headerMap, ['studentname', 'name', 'traineename', 'fullname', 'nameofstudent']);

        // Prevent header field names from ever becoming student records
        $knownFieldLabels = [
            'mrno', 'mr', 'familyinformation', 'fathersname', 'fathername', 'father',
            'mothersname', 'mothername', 'mother', 'siblings', 'address', 'contactnoparents',
            'contactno', 'studentid', 'studentname', 'occupation', 'trade', 'course', 'session',
            'batch', 'batchno', 'grade', 'phone', 'mobile', 'email', 'religion', 'ethnic', 'pwd'
        ];
        $idNorm = preg_replace('/[^a-z0-9]/', '', strtolower($studentId));
        $nameNorm = preg_replace('/[^a-z0-9]/', '', strtolower($studentName));
        if (in_array($idNorm, $knownFieldLabels) || in_array($nameNorm, ['studentname', 'fullname', 'nameofstudent', 'idno', 'mrno'])) {
            return null;
        }

        // Positional fallback if headers were not matched
        if (empty($studentId) && empty($studentName) && empty($headerMap)) {
            $studentId = self::clean($row[0] ?? $row[1] ?? '');
            $studentName = self::clean($row[1] ?? $row[2] ?? '');
        }

        if (empty($studentId) && empty($studentName)) {
            return null;
        }

        // Gender
        $genderRaw = self::getFieldVal($row, $headerMap, ['gender', 'sex'], $row[2] ?? '');
        $genderNorm = match (strtolower(trim($genderRaw))) {
            'm', 'male' => 'Male',
            'f', 'female' => 'Female',
            default => !empty($genderRaw) ? ucfirst(strtolower(trim($genderRaw))) : null,
        };

        // Course Type
        $courseType = self::getFieldVal($row, $headerMap, ['coursetype', 'course', 'type'], $row[3] ?? 'Short Course');

        // Occupation
        $occupation = self::getFieldVal($row, $headerMap, ['occupation', 'trade', 'technology', 'subject'], $row[4] ?? '');

        // RPL Course
        $rplCourse = self::getFieldVal($row, $headerMap, ['rplcourse', 'rpl'], $row[5] ?? '');

        // Academic Year
        $academicYearRaw = self::getFieldVal($row, $headerMap, ['academicyear', 'year', 'sessionyear']);
        $academicYear = null;
        if (!empty($academicYearRaw)) {
            if (preg_match('/(20\d{2}|19\d{2})/', (string)$academicYearRaw, $ym)) {
                $academicYear = $ym[1];
            } elseif (is_numeric($academicYearRaw) && (int)$academicYearRaw >= 1990 && (int)$academicYearRaw <= 2050) {
                $academicYear = (string)(int)$academicYearRaw;
            }
        }
        if (!$academicYear && isset($row[6]) && is_numeric($row[6]) && (int)$row[6] >= 1990 && (int)$row[6] <= 2050) {
            $academicYear = (string)(int)$row[6];
        }
        if (!$academicYear) {
            $academicYear = date('Y');
        }

        // Session
        $session = self::getFieldVal($row, $headerMap, ['session', 'batchsession'], $row[7] ?? 'Session 1');
        $sessionNumber = 1;
        if (preg_match('/(?:session\s*)?(\d+)/i', $session, $matches)) {
            $sessionNumber = (int)$matches[1];
        }

        // Batch No
        $batchNo = self::getFieldVal($row, $headerMap, ['batchno', 'batch', 'batchname'], $row[8] ?? '');

        // DOB
        $dobRaw = self::getFieldVal($row, $headerMap, ['dob', 'dateofbirth', 'birthdate'], $row[9] ?? '');
        $dob = self::parseDate($dobRaw);

        // Father's Name
        $fatherName = self::getFieldVal($row, $headerMap, ['fathersname', 'fathername', 'father', 'guardianname'], $row[10] ?? '');

        // Mother's Name
        $motherName = self::getFieldVal($row, $headerMap, ['mothersname', 'mothername', 'mother']);

        // Phone
        $phone = self::getFieldVal($row, $headerMap, ['phone', 'mobile', 'cell', 'contact', 'phoneno', 'mobileno']);

        // Grade
        $grade = self::getFieldVal($row, $headerMap, ['grade', 'result', 'gpa', 'gradeperformance']);

        // Address
        $companyAddress = self::getFieldVal($row, $headerMap, ['companyaddress', 'officeaddress']);
        $presentAddress = self::getFieldVal($row, $headerMap, ['address', 'presentaddress', 'residentialaddress']) ?: $companyAddress;

        // Designation
        $designation = self::getFieldVal($row, $headerMap, ['designation', 'position', 'jobtitle']);

        // Company Name
        $companyName = self::getFieldVal($row, $headerMap, ['companyname', 'company', 'organization', 'employer']);

        // Ethnic & PWD
        $ethnicRaw = self::getFieldVal($row, $headerMap, ['ethnic', 'ethnicgroup', 'tribal'], 'No');
        $ethnic = (strtolower($ethnicRaw) === 'yes' || strtolower($ethnicRaw) === 'y') ? 'Yes' : 'No';

        $pwdRaw = self::getFieldVal($row, $headerMap, ['pwd', 'disability', 'disabled'], 'No');
        $pwd = (strtolower($pwdRaw) === 'yes' || strtolower($pwdRaw) === 'y') ? 'Yes' : 'No';

        // Religion
        $religion = self::getFieldVal($row, $headerMap, ['religion', 'faith'], 'Islam');

        // Remarks & Family Info
        $remarks = self::getFieldVal($row, $headerMap, ['remarks', 'comment', 'note']);
        $familyInfo = self::getFieldVal($row, $headerMap, ['familyinformation', 'familyinfo']);
        if (empty($remarks) && !empty($familyInfo)) {
            $remarks = $familyInfo;
        }

        // Parent Phone & Siblings
        $parentPhone = self::getFieldVal($row, $headerMap, ['parentphone', 'parentsphone', 'parentcontact', 'contactnoparents', 'contactparents', 'guardiancontact', 'guardianphone']);
        $siblingsRaw = self::getFieldVal($row, $headerMap, ['siblings', 'numberofsiblings', 'sibling']);
        $siblings = is_numeric($siblingsRaw) ? (int)$siblingsRaw : null;

        // Admission Date
        $admissionDateRaw = self::getFieldVal($row, $headerMap, ['dateofadmission', 'admissiondate', 'admission']);
        $admissionDate = self::parseDate($admissionDateRaw);
        if (!$admissionDate && is_numeric($academicYear)) {
            try {
                $admissionDate = Carbon::create((int)$academicYear, 1, 1)->format('Y-m-d');
            } catch (\Throwable $e) {
                $admissionDate = null;
            }
        }

        // Auto-generate ID if missing
        if (empty($studentId)) {
            $studentId = StudentIdService::generateId($occupation, $academicYear ?: date('Y'), $session);
        }

        $occupationCode = StudentIdService::resolveOccupationCode($occupation);

        // Certificate status
        $certStatus = 'Not Issued';
        if (!empty($grade) && !in_array(strtolower($grade), ['fail', 'f', 'absent'])) {
            $certStatus = 'Issued';
        }

        // Job Status
        $jobStatus = !empty($companyName) || !empty($designation) ? 'Employed' : 'Not Yet Employed';

        $age = null;
        if ($dob) {
            try {
                $age = Carbon::parse($dob)->age;
            } catch (\Throwable $e) {
                $age = null;
            }
        }

        return [
            'student_id' => $studentId,
            'student_name' => $studentName,
            'gender' => $genderNorm,
            'date_of_admission' => $admissionDate,
            'course_type' => $courseType ?: 'Short Course',
            'course_duration_months' => 3,
            'section' => '',
            'occupation' => $occupation,
            'occupation_code' => $occupationCode,
            'rpl_course' => $rplCourse,
            'academic_year' => $academicYear ?: date('Y'),
            'session' => $session ?: 'Session 1',
            'session_number' => $sessionNumber,
            'batch_no' => $batchNo,
            'shift' => 'Day',
            'donor' => 'IMI',
            'phone' => $phone,
            'dob' => $dob,
            'age' => $age,
            'nid_birth_cert' => '',
            'parent_phone' => $parentPhone,
            'educational_qualification' => '',
            'father_name' => $fatherName,
            'mother_name' => $motherName,
            'siblings' => $siblings,
            'marital_status' => null,
            'address' => $presentAddress,
            'present_address' => $presentAddress,
            'permanent_address' => '',
            'religion' => $religion,
            'ethnic' => $ethnic,
            'pwd' => $pwd,
            'payment_status' => null,
            'training_status' => 'Completed',
            'total_marks' => null,
            'grade' => $grade ?: null,
            'nsda_bteb_result' => null,
            'certificate_status' => $certStatus,
            'certificate_no' => $certStatus === 'Issued' ? ('MCTSC-' . ($academicYear ?: date('Y')) . '-' . $studentId) : null,
            'certificate_issue_date' => $certStatus === 'Issued' ? now()->format('Y-m-d') : null,
            'certificate_printed_at' => null,
            'certificate_distributed_at' => null,
            'certificate_reissued_at' => null,
            'certificate_reissue_count' => 0,
            'job_status' => $jobStatus,
            'company_name' => $companyName,
            'company_type' => null,
            'employer_phone' => null,
            'company_address' => $companyAddress,
            'designation' => $designation,
            'employment_type' => null,
            'joining_date' => null,
            'salary' => null,
            'remarks' => $remarks,
            'created_by' => $createdBy,
        ];
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("mapRowToStudentData error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
            return null;
        }
    }

    /**
     * Execute full import and write to database in chunks
     */
    public static function executeImport(array $rawRows, string $originalFileName, ?int $userId = null): StudentImportBatch
    {
        $batch = StudentImportBatch::create([
            'file_name' => $originalFileName,
            'imported_by' => $userId,
            'total_rows' => count($rawRows),
            'successful_rows' => 0,
            'duplicate_rows' => 0,
            'error_rows' => 0,
            'status' => 'processing',
        ]);

        $headerRowIdx = -1;
        $headerMap = self::buildHeaderMap($rawRows, $headerRowIdx);
        $dataRows = ($headerRowIdx >= 0) ? array_slice($rawRows, $headerRowIdx + 1) : $rawRows;

        $existingIds = [];
        try {
            $existingIds = Student::pluck('student_id')->filter()->flip()->toArray();
        } catch (\Throwable $e) {
            $existingIds = [];
        }

        $chunkSize = 100;
        $chunks = array_chunk($dataRows, $chunkSize);

        $success = 0;
        $duplicates = 0;
        $errors = 0;
        $errorLogs = [];
        $occupationsToRecord = [];
        $batchesToRecord = [];

        foreach ($chunks as $chunk) {
            $insertData = [];
            foreach ($chunk as $row) {
                try {
                    $mapped = self::mapRowToStudentData($row, $headerMap, $userId);
                    if ($mapped === null) {
                        continue;
                    }

                    if (!empty($mapped['student_id']) && isset($existingIds[$mapped['student_id']])) {
                        $duplicates++;
                        continue;
                    }

                    $insertData[] = array_merge($mapped, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if (!empty($mapped['student_id'])) {
                        $existingIds[$mapped['student_id']] = true;
                    }

                    if (!empty($mapped['batch_no'])) {
                        $batchesToRecord[$mapped['batch_no']] = [
                            'name' => $mapped['batch_no'],
                            'academic_year' => $mapped['academic_year'] ?? date('Y'),
                        ];
                    }

                    if (!empty($mapped['occupation'])) {
                        $occupationsToRecord[$mapped['occupation']] = [
                            'name' => $mapped['occupation'],
                            'code' => $mapped['occupation_code'] ?? 'S',
                        ];
                    }
                } catch (\Throwable $e) {
                    $errors++;
                    $errorLogs[] = "Row error: " . $e->getMessage();
                }
            }

            if (!empty($insertData)) {
                try {
                    Student::insert($insertData);
                    $success += count($insertData);
                } catch (\Throwable $e) {
                    // Fallback to row-by-row on batch insert failure
                    foreach ($insertData as $rec) {
                        try {
                            Student::create($rec);
                            $success++;
                        } catch (\Throwable $innerEx) {
                            $errors++;
                            $errorLogs[] = "Insert error [{$rec['student_id']}]: " . $innerEx->getMessage();
                        }
                    }
                }
            }
        }

        // Record any new occupations
        foreach ($occupationsToRecord as $occ) {
            Occupation::firstOrCreate(
                ['name' => $occ['name']],
                ['code' => $occ['code'], 'status' => 'active']
            );
        }

        // Record any new batches
        foreach ($batchesToRecord as $b) {
            StudentBatch::firstOrCreate(
                ['name' => $b['name'], 'academic_year' => $b['academic_year']],
                ['status' => 'active']
            );
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
     * Safe date parser handling multiple formats and Excel serial numbers
     */
    protected static function parseDate(?string $value): ?string
    {
        if (empty($value) || $value === '0000-00-00' || $value === 'NULL' || $value === '!DOB?') {
            return null;
        }

        $trimmed = strtolower(trim((string)$value));
        if (in_array($trimmed, ['male', 'female', 'yes', 'no', 'islam', 'hindu', 'christian', 'buddhist', 'employed', 'unemployed', 'short course'])) {
            return null;
        }

        // Excel numeric serial date (e.g. 44123 for Oct 18, 2020)
        if (is_numeric($value) && (float)$value > 1000 && (float)$value < 90000) {
            try {
                $unix = ((float)$value - 25569) * 86400;
                return gmdate('Y-m-d', (int)$unix);
            } catch (\Throwable $e) {
                // fallback
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            if (preg_match('/^(\d{1,2})[\/\.-](\d{1,2})[\/\.-](\d{2,4})$/', $value, $m)) {
                $year = (int)$m[3];
                if ($year < 100) $year += ($year > 50 ? 1900 : 2000);
                try {
                    return Carbon::create($year, (int)$m[2], (int)$m[1])->format('Y-m-d');
                } catch (\Throwable $ex) {}
            }
            return null;
        }
    }

    /**
     * Clean string: UTF-8 normalize, strip non-breaking spaces
     */
    public static function clean(?string $val): string
    {
        if ($val === null) {
            return '';
        }

        $clean = str_replace(["\xc2\xa0", "\xa0", "\r"], [' ', ' ', ''], $val);
        $clean = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $clean);
        return trim($clean);
    }
}
