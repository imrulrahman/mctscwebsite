<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'date_of_admission' => 'date',
            'dob' => 'date',
            'certificate_issue_date' => 'date',
            'certificate_printed_at' => 'datetime',
            'certificate_distributed_at' => 'datetime',
            'certificate_reissued_at' => 'datetime',
            'certificate_reissue_count' => 'integer',
            'course_duration_months' => 'integer',
            'session_start_date' => 'date',
            'session_end_date' => 'date',
            'joining_date' => 'date',
            'age' => 'integer',
            'session_number' => 'integer',
            'viva_mark' => 'decimal:2',
            'theory_mark' => 'decimal:2',
            'practical_mark' => 'decimal:2',
            'total_marks' => 'decimal:2',
            'salary' => 'decimal:2',
        ];
    }

    /* -------------------------------------------------------------------------
     * Scopes
     * ---------------------------------------------------------------------- */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        $clean = trim($term);
        return $query->where(function (Builder $q) use ($clean) {
            $q->where('student_id', 'like', "%{$clean}%")
              ->orWhere('student_name', 'like', "%{$clean}%")
              ->orWhere('student_name_bn', 'like', "%{$clean}%")
              ->orWhere('phone', 'like', "%{$clean}%")
              ->orWhere('alt_phone', 'like', "%{$clean}%")
              ->orWhere('parent_phone', 'like', "%{$clean}%")
              ->orWhere('nid_birth_cert', 'like', "%{$clean}%")
              ->orWhere('batch_no', 'like', "%{$clean}%");
        });
    }

    public function scopeAcademicYear(Builder $query, ?string $year): Builder
    {
        if (empty($year) || $year === 'all' || $year === 'All') {
            return $query;
        }

        // Handle formats like "2024" or "2024-2025" or "2024 – 2025"
        if (str_contains($year, '-')) {
            $parts = explode('-', str_replace(' ', '', $year));
            return $query->where(function (Builder $q) use ($parts, $year) {
                $q->where('academic_year', $year)
                  ->orWhere('academic_year', $parts[0])
                  ->orWhere('academic_year', 'like', $parts[0] . '%');
            });
        }

        return $query->where('academic_year', $year);
    }

    public function scopeBatch(Builder $query, ?string $batch): Builder
    {
        if (empty($batch) || $batch === 'all' || $batch === 'All') {
            return $query;
        }
        return $query->where('batch_no', $batch);
    }

    public function scopeDonor(Builder $query, ?string $donor): Builder
    {
        if (empty($donor) || $donor === 'all' || $donor === 'All') {
            return $query;
        }
        return $query->where('donor', $donor);
    }

    public function scopeOccupation(Builder $query, ?string $occupation): Builder
    {
        if (empty($occupation) || $occupation === 'all' || $occupation === 'All') {
            return $query;
        }
        return $query->where('occupation', $occupation);
    }

    public function scopeGender(Builder $query, ?string $gender): Builder
    {
        if (empty($gender) || $gender === 'all' || $gender === 'All') {
            return $query;
        }
        return $query->where('gender', $gender);
    }

    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        if (empty($category) || $category === 'all' || $category === 'All') {
            return $query;
        }
        if (strtolower($category) === 'ethnic') {
            return $query->where('ethnic', 'Yes');
        }
        if (strtolower($category) === 'pwd') {
            return $query->where('pwd', 'Yes');
        }
        return $query;
    }

    public function scopeTrainingStatus(Builder $query, ?string $status): Builder
    {
        if (empty($status) || $status === 'all' || $status === 'All') {
            return $query;
        }
        return $query->where('training_status', $status);
    }

    public function scopeJobStatus(Builder $query, ?string $status): Builder
    {
        if (empty($status) || $status === 'all' || $status === 'All') {
            return $query;
        }
        return $query->where('job_status', $status);
    }

    public function scopeCertificateStatus(Builder $query, ?string $status): Builder
    {
        if (empty($status) || $status === 'all' || $status === 'All') {
            return $query;
        }
        return $query->where('certificate_status', $status);
    }

    /* -------------------------------------------------------------------------
     * Accessors & Helpers
     * ---------------------------------------------------------------------- */
    public function getFullNameAttribute(): string
    {
        return (string)($this->student_name ?: '');
    }

    public function getOccupationNameAttribute(): string
    {
        return (string)($this->occupation ?: '');
    }

    public function getAvatarInitialsAttribute(): string
    {
        $name = trim($this->student_name ?: 'Student');
        $words = preg_split('/\s+/', $name);
        if (count($words) >= 2) {
            return strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[count($words) - 1], 0, 1));
        }
        return strtoupper(mb_substr($name, 0, 2));
    }

    public function getAvatarColorAttribute(): array
    {
        // Colors palette matching design reference initials (soft pastel backgrounds with bold readable text)
        $palettes = [
            ['bg' => '#E0F2FE', 'text' => '#0369A1'], // Sky
            ['bg' => '#ECFDF5', 'text' => '#047857'], // Emerald
            ['bg' => '#FEE2E2', 'text' => '#B91C1C'], // Rose
            ['bg' => '#EDE9FE', 'text' => '#6D28D9'], // Violet
            ['bg' => '#FEF3C7', 'text' => '#B45309'], // Amber
            ['bg' => '#F3E8FF', 'text' => '#7E22CE'], // Purple
            ['bg' => '#CCFBF1', 'text' => '#0F766E'], // Teal
            ['bg' => '#FFE4E6', 'text' => '#BE123C'], // Pink
            ['bg' => '#E2E8F0', 'text' => '#334155'], // Slate
        ];

        $hash = crc32($this->student_id ?: $this->student_name);
        $idx = abs($hash) % count($palettes);
        return $palettes[$idx];
    }

    public function getCategoryLabelAttribute(): string
    {
        $cats = [];
        if (strtolower($this->ethnic) === 'yes') {
            $cats[] = 'Ethnic';
        }
        if (strtolower($this->pwd) === 'yes') {
            $cats[] = 'PWD';
        }
        return !empty($cats) ? implode(' / ', $cats) : '—';
    }

    public function getCertificateNormalizedStatusAttribute(): string
    {
        $st = $this->certificate_status;
        if (empty($st) || in_array(strtolower($st), ['not issued', 'pending', 'not eligible', 'draft'])) {
            return 'Not Issued';
        }
        if (strtolower($st) === 'distributed') {
            return 'Distributed';
        }
        if (strtolower($st) === 'printed') {
            return 'Printed';
        }
        return 'Issued';
    }

    public function getCertificateActionDateAttribute(): ?string
    {
        $status = $this->certificate_normalized_status;
        if ($status === 'Distributed' && $this->certificate_distributed_at) {
            return $this->certificate_distributed_at->format('d M Y');
        }
        if ($status === 'Printed' && $this->certificate_printed_at) {
            return $this->certificate_printed_at->format('d M Y');
        }
        if ($status === 'Issued') {
            if ($this->certificate_issue_date) {
                return $this->certificate_issue_date->format('d M Y');
            }
            if ($this->updated_at) {
                return $this->updated_at->format('d M Y');
            }
        }
        return null;
    }

    public function getComputedSessionDatesAttribute(): array
    {
        if ($this->session_start_date && $this->session_end_date) {
            return [
                'start' => $this->session_start_date->format('F j, Y'),
                'end' => $this->session_end_date->format('F j, Y'),
            ];
        }

        $year = (int)($this->academic_year ?: date('Y'));
        if ($year < 2000) $year = 2000 + ($year % 100);

        $sNum = $this->session_number ?: 1;
        $startMonth = match ($sNum) {
            1 => 1,  // January
            2 => 4,  // April
            3 => 7,  // July
            4 => 10, // October
            default => 1,
        };

        $start = Carbon::create($year, $startMonth, 1);
        $durationMonths = (int)($this->course_duration_months ?: 3);
        $end = (clone $start)->addMonths($durationMonths)->subDay();

        return [
            'start' => $start->format('F j, Y'),
            'end' => $end->format('F j, Y'),
        ];
    }

    public function getStatusBadgeClassAttribute(): string
    {
        $status = strtolower($this->training_status ?? 'active');
        return match ($status) {
            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'active', 'running' => 'bg-green-100 text-green-800 border-green-300',
            'dropped' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    /* -------------------------------------------------------------------------
     * Relationships
     * ---------------------------------------------------------------------- */
    public function admission(): BelongsTo
    {
        return $this->belongsTo(AdmissionApplication::class, 'admission_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
