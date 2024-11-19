<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePeriodicSalaryIncrease extends Model
{

    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'employee_periodic_salary_increases';
    protected $fillable = [
        'number_psi',
        'date_periodic_salary_increase',
        'employee_id',
        'old_basic_salary_id',
        'new_basic_salary_id',
        'total_basic_salary',
        'docs_letter',
        'docs_archive',
        'users_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_periodic_salary_increase' => 'date',
    ];

    /**
     * Get the employee that owns the periodic salary increase.
     */
    public function employee()
    {
        return $this->belongsTo(Employees::class);
    }

    /**
     * Get the basic salary associated with the periodic salary increase.
     */
    public function basicSalary()
    {
        return $this->belongsTo(MasterEmployeeBasicSalary::class, 'basic_salary_id');
    }

    public function employeePeriodic()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function oldBasicSalary()
    {
        return $this->belongsTo(MasterEmployeeBasicSalary::class, 'old_basic_salary_id');
    }

    public function newBasicSalary()
    {
        return $this->belongsTo(MasterEmployeeBasicSalary::class, 'new_basic_salary_id');
    }
}
