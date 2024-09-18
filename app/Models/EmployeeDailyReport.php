<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeeDailyReport extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'employee_id',
        'daily_report_date',
        'work_description',
        'work_status',
        'desc',
        'image',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class);
    }
}
