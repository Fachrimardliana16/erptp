<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeBenefit extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'employee_benefits';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    /**
     * Get the employee that owns the benefit.
     */
    protected $fillable = [
        'employee_grade_benefit_id',
        'employee_id',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employeeGradeBenefit()
    {
        return $this->belongsTo(MasterEmployeeGradeBenefit::class, 'employee_grade_benefit_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }
}
