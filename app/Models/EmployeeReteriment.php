<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeReteriment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'employee_reteriments';

    protected $fillable = [
        'registration_number',
        'employee_id',
    ];
}
