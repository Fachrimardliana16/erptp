<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeBasicSalary extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_basic_salary';
    protected $fillable = [
        'employee_service_grade_id',
        'employee_grade_id',
        'amount',
        'desc',
        'users_id'
    ];

    public $timestamps = true;

    public function employeePromotionsAsOld()
    {
        return $this->hasMany(EmployeePromotion::class, 'old_basic_salary', 'id'); // Perbaikan
    }

    public function employeePromotionsAsNew()
    {
        return $this->hasMany(EmployeePromotion::class, 'new_basic_salary', 'id'); // Perbaikan
    }

    public function employeeBasic()
    {
        return $this->hasMany(Employees::class, 'basic_salary_id', 'id');
    }

    public function employeeGrade()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'employee_grade_id');
    }
    // Di MasterEmployeeBasicSalary
    public function serviceGrade()  // Ganti dari basicSalaries
    {
        return $this->belongsTo(MasterEmployeeServiceGrade::class, 'employee_service_grade_id');
    }
}
