<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeePeriodicSalaryIncrease extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use HasFactory, HasUuids;

    protected $table = 'employee_periodic_salary_increases';
    protected $fillable = [
        'number_psi',
        'date_periodic_salary_increase',
        'employee_id',
        'basic_salary',
        'salary_increase',
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
}
