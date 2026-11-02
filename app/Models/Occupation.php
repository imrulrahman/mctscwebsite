<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Occupation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'sector',
        'status',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'occupation', 'name');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(StudentBatch::class);
    }
}
