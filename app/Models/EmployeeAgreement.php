<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeeAgreement extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'employee_agreement';

    protected $fillable = [
        'job_application_archives_id',
        'agreement_number',
        'name',
        'agreement_id',
        'employee_position_id',
        'employment_status_id',
        'basic_salary_id',
        'agreement_date_start',
        'agreement_date_end',
        'departments_id',
        'sub_department_id',
        'docs',
        'users_id',
    ];

    public function agreement()
    {
        return $this->belongsTo(MasterEmployeeAgreement::class, 'agreement_id', 'id');
    }

    public function agreementPosition()
    {
        return $this->belongsTo(MasterEmployeePosition::class, 'employee_position_id', 'id');
    }

    public function agreementStatus()
    {
        return $this->belongsTo(MasterEmployeeStatusEmployemnt::class, 'employment_status_id', 'id');
    }

    public function agreementJob()
    {
        return $this->belongsTo(EmployeeJobApplicationArchives::class, 'job_application_archives_id', 'id');
    }

    public function agreementDepartement()
    {
        return $this->belongsTo(MasterDepartments::class, 'departments_id', 'id');
    }

    public function agreementSubDepartement()
    {
        return $this->belongsTo(MasterSubDepartments::class, 'sub_department_id', 'id');
    }

    // Menambahkan relasi untuk grade_id
    public function basicSalary()
    {
        return $this->belongsTo(MasterEmployeeBasicSalary::class, 'basic_salary_id');
    }

    // Accessor untuk grade_id
    public function getGradeIdAttribute()
    {
        return $this->basicSalary?->employee_grade_id;
    }

    // Accessor untuk amount
    public function getAmountAttribute()
    {
        return $this->basicSalary?->amount;
    }

    // Mutator untuk basic_salary_id
    public function setBasicSalaryIdAttribute($value)
    {
        $this->attributes['basic_salary_id'] = $value;
    }

    // Untuk mendapatkan grade melalui basic salary
    public function grade()
    {
        return $this->hasOneThrough(
            MasterEmployeeGrade::class,
            MasterEmployeeBasicSalary::class,
            'id', // Foreign key on master_employee_basic_salary table
            'id', // Foreign key on master_employee_grade table
            'basic_salary_id', // Local key on employee_agreement table
            'employee_grade_id' // Local key on master_employee_basic_salary table
        );
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'basicSalary.employeeGrade.name',
            'basicSalary.amount',
            'agreement_number',
            'name',
        ];
    }
}
