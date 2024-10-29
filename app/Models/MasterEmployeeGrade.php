<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeGrade extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_grade';
    protected $fillable = ['name', 'desc', 'users_id'];

    public $timestamps = true;

    public function gradeBenefits()
    {
        return $this->hasMany(MasterEmployeeGradeBenefit::class);
    }

    public function gradeCuts()
    {
        return $this->hasMany(MasterEmployeeGradeSalaryCuts::class, 'grade_id');
    }

    public function employees()
    {
        return $this->hasMany(Employees::class, 'employee_grade_id', 'id');
    }

    public function promotionsAsOld()
    {
        return $this->hasMany(EmployeePromotion::class, 'old_grade_id', 'id'); // Perbaikan
    }

    public function promotionsAsNew()
    {
        return $this->hasMany(EmployeePromotion::class, 'new_grade_id', 'id'); // Perbaikan
    }

    public function gradeSalary()
    {
        return $this->hasMany(MasterEmployeeBasicSalary::class, 'employee_grade_id', 'id');
    }

    public function gradeAssigned()
    {
        return $this->hasMany(EmployeeAssignmentLetters::class, 'grade_id', 'id');
    }
}
