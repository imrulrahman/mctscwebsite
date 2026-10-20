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
        'course_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'payable_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'approved_at' => 'datetime',
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
            if (!isset($app->course_fee)) {
                $app->course_fee = 6000.00;
            }
            if (!isset($app->discount)) {
                $app->discount = 0.00;
            }
            $app->payable_amount = max(0, (float)$app->course_fee - (float)$app->discount);

            if ($app->payable_amount <= 0) {
                $app->payment_status = 'free';
                $app->paid_amount = 0.00;
            }

            if (empty($app->voucher_no)) {
                $app->voucher_no = 'VR-' . ($app->tracking_no ?: date('YmdHis'));
            }
        });
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isApproved(): bool
    {
        return in_array($this->status, ['accepted', 'approved']);
    }

    public function isFreeProject(): bool
    {
        return (float)($this->payable_amount ?? $this->course_fee ?? 0) <= 0;
    }

    public function isPaid(): bool
    {
        if ($this->isFreeProject()) {
            return true;
        }
        if ($this->payment_status === 'paid' || $this->payment_status === 'free') {
            return true;
        }
        return (float)$this->paid_amount >= (float)$this->payable_amount && (float)$this->payable_amount > 0;
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
