<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EmployeePayroll extends Model
{
    use HasFactory, HasUuids;
    use SoftDeletes, LogsActivity;

    protected $table = 'employee_payroll';

    protected $casts = [
        'basic_salary' => 'encrypted',
        'netto' => 'encrypted',
    ];

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    protected $fillable = [
        'periode',
        'employee_id',
        'status_id',
        'grade_id',
        'position_id',
        'salary_id',
        'benefits_1',
        'benefits_2',
        'benefits_3',
        'benefits_4',
        'benefits_5',
        'benefits_6',
        'benefits_7',
        'benefits_8',
        'rounding',
        'incentive',
        'backpay',
        'gross_amount',
        'absence_count',
        'paycut_1',
        'paycut_2',
        'paycut_3',
        'paycut_4',
        'paycut_5',
        'paycut_6',
        'paycut_7',
        'paycut_8',
        'paycut_9',
        'paycut_10',
        'netto',
        'desc',
        'cut_amount',
        'users_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function status()
    {
        return $this->belongsTo(MasterEmployeeStatusEmployemnt::class, 'status_id');
    }

    public function grade()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'grade_id');
    }

    public function position()
    {
        return $this->belongsTo(MasterEmployeePosition::class, 'position_id');
    }

    public function salary()
    {
        return $this->belongsTo(EmployeeSalary::class, 'salary_id');
    }
}
