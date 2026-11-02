<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentImportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'imported_by',
        'total_rows',
        'successful_rows',
        'duplicate_rows',
        'error_rows',
        'status',
        'error_log',
    ];

    protected function casts(): array
    {
        return [
            'total_rows' => 'integer',
            'successful_rows' => 'integer',
            'duplicate_rows' => 'integer',
            'error_rows' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by');
    }
}
