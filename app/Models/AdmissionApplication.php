<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'dob' => 'date',
        'monthly_income' => 'decimal:2',
        'yearly_income' => 'decimal:2',
        'daily_income_per_person' => 'decimal:2',
        'monthly_cash_income' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($app) {
            if (empty($app->tracking_no)) {
                $year = date('Y');
                $count = static::whereYear('created_at', $year)->count() + 1;
                $app->tracking_no = 'MCTSC-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
            if (empty($app->name)) {
                $app->name = $app->trainee_name_en ?: ($app->trainee_name_bn ?: 'Trainee');
            }
            if (empty($app->phone)) {
                $app->phone = $app->contact_no ?: '';
            }
        });
    }

    public function photoUrl(): string
    {
        return $this->photo ? storage_asset($this->photo, 'assets/images/user-placeholder.png') : asset('assets/images/user-placeholder.png');
    }

    public function signatureUrl(): ?string
    {
        return $this->signature_photo ? storage_asset($this->signature_photo) : null;
    }

    public function identDocUrl(): ?string
    {
        return $this->ident_doc ? storage_asset($this->ident_doc) : null;
    }
}
