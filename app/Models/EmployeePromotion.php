<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeePromotion extends Model
{

    use HasUuids, HasFactory;

    protected $table = 'employee_promotion';

    protected $fillable = [
        'decision_letter_number',
        'promotion_date',
        'employee_id',
        'old_grade_id',
        'new_grade_id',
        'old_basic_salary_id',
        'new_basic_salary_id',
        'users_id',
    ];

    public function employeePromotion()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function oldGrade()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'old_grade_id', 'id');
    }

    public function newGrade()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'new_grade_id', 'id');
    }

    public function oldBasicSalary()
    {
        return $this->belongsTo(MasterEmployeeBasicSalary::class, 'old_basic_salary_id', 'id');
    }

    public function newBasicSalary()
    {
        return $this->belongsTo(MasterEmployeeBasicSalary::class, 'new_basic_salary_id', 'id');
    }
}
