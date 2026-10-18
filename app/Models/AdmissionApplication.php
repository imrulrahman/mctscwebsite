<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'course', 'education', 'message', 'status'];
}
