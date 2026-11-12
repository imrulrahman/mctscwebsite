<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'tracking_no',
        'site_content_id',
        'post_name',
        'applicant_name',
        'father_name',
        'mother_name',
        'dob',
        'gender',
        'nationality',
        'religion',
        'nid_no',
        'birth_reg_no',
        'passport_no',
        'marital_status',
        'spouse_name',
        'quota',
        'departmental_candidate',
        'present_address',
        'permanent_address',
        'mobile_no',
        'email',
        'education',
        'experiences',
        'other_qualifications',
        'photo_path',
        'signature_path',
        'cv_path',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'present_address' => 'array',
            'permanent_address' => 'array',
            'education' => 'array',
            'experiences' => 'array',
        ];
    }

    public function jobCircular(): BelongsTo
    {
        return $this->belongsTo(SiteContent::class, 'site_content_id');
    }
}
