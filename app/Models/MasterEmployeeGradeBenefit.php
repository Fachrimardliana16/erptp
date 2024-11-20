<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeGradeBenefit extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_grade_benefit';
    protected $fillable = ['benefit_id', 'grade_id', 'amount', 'desc', 'users_id'];

    public $timestamps = true;

    public function gradeBenefits()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'grade_id');
    }

    public function benefit()
    {
        return $this->belongsTo(MasterEmployeeBenefit::class, 'benefit_id');
    }

    public function employeeGradeBenefit()
    {
        return $this->hasMany(EmployeeBenefit::class, 'employee_grade_benefit_id');
    }

    public function masterBenefit()
    {
        return $this->belongsTo(MasterEmployeeBenefit::class, 'benefit_id');
    }
}
