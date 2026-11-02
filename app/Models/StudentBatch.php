<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'occupation_id',
        'academic_year',
        'status',
    ];

    public function occupation(): BelongsTo
    {
        return $this->belongsTo(Occupation::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'batch_no', 'name');
    }
}
