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

    protected $keyType = 'string';

    protected $fillable = [
        'employee_grade_benefit_id',
        'employee_id',
        'description',
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
