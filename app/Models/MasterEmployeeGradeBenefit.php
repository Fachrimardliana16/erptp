<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeGradeBenefit extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_grade_banefit';
    protected $fillable = ['benefit_id', 'grade_id', 'amount', 'desc', 'users_id'];

    public $timestamps = true;

    public function GradeBenefit()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'grade_id');
    }

    public function BenefitGrade()
    {
        return $this->belongsTo(MasterEmployeeBenefit::class, 'benefit_id');
    }


    public function benefit1()
    {
        return $this->belongsTo(EmployeeSalary::class, 'benefitsid_1');
    }
    public function benefit2()
    {
        return $this->belongsTo(EmployeeSalary::class, 'benefitsid_2');
    }
    public function benefit3()
    {
        return $this->belongsTo(EmployeeSalary::class, 'benefitsid_3');
    }
    public function benefit4()
    {
        return $this->belongsTo(EmployeeSalary::class, 'benefitsid_4');
    }

    public function benefit5()
    {
        return $this->belongsTo(EmployeeSalary::class, 'benefitsid_5');
    }
    public function benefit6()
    {
        return $this->belongsTo(EmployeeSalary::class, 'benefitsid_6');
    }
    public function benefit7()
    {
        return $this->belongsTo(EmployeeSalary::class, 'benefitsid_7');
    }
    public function benefit8()
    {
        return $this->belongsTo(EmployeeSalary::class, 'benefitsid_8');
    }
}
