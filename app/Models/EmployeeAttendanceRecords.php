<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeeAttendanceRecords extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'employee_attendance_records';
    protected $fillable = [
        'pin',
        'employee_name',
        'attendance_time',
        'state',
        'verification',
        'work_code',
        'reserved',
        'device',
        'picture',
        'users_id'
    ];
}
