<?php

namespace App\Services;

use App\Models\Occupation;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentIdService
{
    /**
     * Fallback mapping of known occupations to institutional codes
     */
    protected static array $codeMap = [
        'computer office application' => 'C',
        'industrial sewing machine operation' => 'S',
        'electrical installation & maintenance' => 'E',
        'tailoring & dress making' => 'T',
        'driving' => 'D',
        'occupational safety and health' => 'OSH',
        'recognition of prior learning' => 'RPL',
        'graphics designing' => 'GD',
        'graphics design for freelancing' => 'E',
        'plumbing & pipe fitting' => 'P',
        'refrigeration & air conditioning' => 'R',
        'it support service' => 'S',
    ];

    /**
     * In-memory cache of DB occupations
     */
    protected static ?array $cachedDbMap = null;

    /**
     * Resolve the occupation code based on Excel rules
     */
    public static function resolveOccupationCode(?string $occupation, ?string $section = null): string
    {
        $sec = strtoupper(trim((string)$section));
        $occ = trim((string)$occupation);
        $occLower = strtolower($occ);

        // Rule 1: Section is RPL or occupation is RPL
        if ($sec === 'RPL' || str_contains($occLower, 'prior learning') || str_starts_with($occLower, 'rpl')) {
            return 'RPL';
        }

        // Rule 2: Fast in-memory lookup (0 SQL queries)
        if (isset(self::$codeMap[$occLower])) {
            return self::$codeMap[$occLower];
        }

        // Rule 3: Substring match in known code map
        foreach (self::$codeMap as $key => $val) {
            if (str_contains($occLower, $key)) {
                return $val;
            }
        }

        // Rule 4: Check database occupations table once (cached in static memory)
        if (self::$cachedDbMap === null) {
            self::$cachedDbMap = [];
            try {
                $dbItems = Occupation::select('name', 'code')->whereNotNull('code')->get();
                foreach ($dbItems as $item) {
                    self::$cachedDbMap[strtolower(trim($item->name))] = strtoupper(trim($item->code));
                }
            } catch (\Throwable $e) {
                // If DB is offline or table missing, continue safely without crashing
                self::$cachedDbMap = [];
            }
        }
        if (isset(self::$cachedDbMap[$occLower])) {
            return self::$cachedDbMap[$occLower];
        }

        // Rule 5: Derive code from initials (e.g. "Civil Engineering" -> "CE")
        $words = preg_split('/[\s&]+/', $occ);
        $initials = '';
        foreach ($words as $w) {
            $cleaned = preg_replace('/[^A-Za-z]/', '', $w);
            if (!empty($cleaned)) {
                $initials .= strtoupper(substr($cleaned, 0, 1));
            }
        }

        return !empty($initials) ? substr($initials, 0, 3) : 'MCT';
    }

    /**
     * Extract 2-digit academic year (e.g. 2024 -> 24, 2025-2026 -> 25)
     */
    public static function resolveYearDigit(string|int|null $year): string
    {
        if (empty($year)) {
            return date('y');
        }

        $yStr = trim((string)$year);
        // If year format is "2025-2026" or "2025 – 2026", grab first year
        if (preg_match('/(\d{4})/', $yStr, $matches)) {
            return substr($matches[1], -2);
        }

        if (preg_match('/(\d{2})/', $yStr, $matches)) {
            return $matches[1];
        }

        return date('y');
    }

    /**
     * Extract session single digit (e.g. "Session 1" -> "1", "Session 2" -> "2")
     */
    public static function resolveSessionDigit(string|int|null $session): string
    {
        if (empty($session)) {
            return '1';
        }

        $sStr = trim((string)$session);
        if (preg_match('/(\d)/', $sStr, $matches)) {
            return $matches[1];
        }

        return '1';
    }

    /**
     * Generate the next unique Student ID following the Excel logic
     * [CODE][YY][SESSION][SERIAL_000]
     */
    public static function generateId(
        ?string $occupation,
        string|int|null $academicYear,
        string|int|null $session = 'Session 1',
        ?string $section = null
    ): string {
        $code = self::resolveOccupationCode($occupation, $section);
        $yy = self::resolveYearDigit($academicYear);
        $sDigit = self::resolveSessionDigit($session);

        $prefix = $code . $yy . $sDigit;

        // In Excel:
        // RPL serial count is per (Academic Year, Section="RPL")
        // Regular serial count is per (Academic Year, Occupation)
        // Find existing student IDs with this prefix or for this group
        return DB::transaction(function () use ($prefix, $code, $yy, $sDigit, $occupation, $academicYear, $section) {
            // Find all existing student IDs that start with this prefix or matching group
            $existingIds = Student::where('student_id', 'like', $prefix . '%')
                ->lockForUpdate()
                ->pluck('student_id')
                ->toArray();

            $maxSerial = 0;
            foreach ($existingIds as $sid) {
                $sub = substr($sid, strlen($prefix));
                if (is_numeric($sub)) {
                    $num = (int)$sub;
                    if ($num > $maxSerial) {
                        $maxSerial = $num;
                    }
                }
            }

            // Also check total count of students matching occupation & academic year as baseline
            $query = Student::query();
            $fullYear = (strlen((string)$academicYear) === 4) ? (string)$academicYear : '20' . $yy;
            $query->where(function ($q) use ($academicYear, $fullYear) {
                $q->where('academic_year', $academicYear)
                  ->orWhere('academic_year', $fullYear);
            });

            if ($code === 'RPL') {
                $query->where(function ($q) {
                    $q->where('section', 'RPL')
                      ->orWhere('occupation', 'Recognition of Prior Learning');
                });
            } else {
                $query->where('occupation', $occupation);
            }

            $countInGroup = $query->count();
            $candidateSerial = max($maxSerial + 1, $countInGroup + 1, 1);

            // Ensure unique constraint safety
            do {
                $candidateId = $prefix . sprintf('%03d', $candidateSerial);
                $exists = Student::where('student_id', $candidateId)->exists();
                if ($exists) {
                    $candidateSerial++;
                }
            } while ($exists);

            return $candidateId;
        });
    }

    /**
     * Preview candidate Student ID for UI form (non-locking)
     */
    public static function previewId(
        ?string $occupation,
        string|int|null $academicYear,
        string|int|null $session = 'Session 1',
        ?string $section = null
    ): string {
        $code = self::resolveOccupationCode($occupation, $section);
        $yy = self::resolveYearDigit($academicYear);
        $sDigit = self::resolveSessionDigit($session);

        $prefix = $code . $yy . $sDigit;

        $existingIds = Student::where('student_id', 'like', $prefix . '%')
            ->pluck('student_id')
            ->toArray();

        $maxSerial = 0;
        foreach ($existingIds as $sid) {
            $sub = substr($sid, strlen($prefix));
            if (is_numeric($sub)) {
                $num = (int)$sub;
                if ($num > $maxSerial) {
                    $maxSerial = $num;
                }
            }
        }

        $nextSerial = $maxSerial + 1;
        return $prefix . sprintf('%03d', $nextSerial);
    }
}
