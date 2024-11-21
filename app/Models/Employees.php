<?php

namespace App\Models;

use App\Models\MasterEmployeePosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employees extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'employees';
    protected $fillable = [
        'nippam',
        'name',
        'place_birth',
        'date_birth',
        'gender',
        'religion',
        'age',
        'address',
        'blood_type',
        'marital_status',
        'phone_number',
        'id_number',
        'familycard_number',
        'npwp_number',
        'bank_account_number',
        'bpjs_tk_number',
        'bpjs_kes_number',
        'rek_dplk_pribadi',
        'rek_dplk_bersama',
        'username',
        'email',
        'password',
        'image',
        'entry_date',
        'probation_appointment_date',
        'length_service',
        'retirement',
        'employment_status_id',
        'master_employee_agreement_id',
        'agreement_date_start',
        'agreement_date_end',
        'employee_education_id',
        'grade_date_start',
        'grade_date_end',
        'basic_salary_id',
        'periodic_salary_date_start',
        'periodic_salary_date_end',
        'employee_position_id',
        'departments_id',
        'sub_department_id',
        'users_id',
    ];

    protected $dates = [
        'date_birth',
        'entry_date',
        'probation_appointment_date',
        'retirement'
    ];


    public function employmentStatus()
    {
        return $this->belongsTo(MasterEmployeeStatusEmployemnt::class, 'employment_status_id', 'id');
    }

    public function employeeAgreement()
    {
        return $this->belongsTo(MasterEmployeeAgreement::class, 'master_employee_agreement_id', 'id');
    }

    public function employeeEducation()
    {
        return $this->belongsTo(MasterEmployeeEducation::class, 'employee_education_id');
    }

    public function employeeGrade()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'employee_grade_id', 'id');
    }



    public function employeePosition()
    {
        return $this->belongsTo(MasterEmployeePosition::class, 'employee_position_id');
    }

    public function EmployeeDepartments()
    {
        return $this->belongsTo(MasterDepartments::class, 'departments_id');
    }

    public function EmployeeSubDepartments()
    {
        return $this->belongsTo(MasterSubDepartments::class, 'sub_department_id');
    }

    public function employeeMutation()
    {
        return $this->hasMany(EmployeeMutations::class, 'employee_id', 'id');
    }

    public function employeePermission()
    {
        return $this->hasMany(EmployeePermission::class, 'employee_id', 'id');
    }

    public function employeePromotion()
    {
        return $this->hasMany(EmployeePromotion::class, 'employee_id', 'id');
    }

    public function employeeAssetMonitoring()
    {
        return $this->hasMany(AssetMonitoring::class, 'employees_id');
    }

    public function AssetsMutationemployee()
    {
        return $this->hasMany(Employees::class, 'employees_id');
    }

    public function employeeFamilies()
    {
        return $this->hasMany(employeeFamilies::class, 'employee_id');
    }

    public function employeeDisposals()
    {
        return $this->hasMany(AssetDisposal::class, 'employee_id', 'id');
    }

    public function employeeBasic()
    {
        return $this->belongsTo(MasterEmployeeBasicSalary::class, 'basic_salary_id', 'id');
    }

    public function agreementEmployee()
    {
        return $this->hasMany(EmployeeAgreement::class, 'employee_id', 'id');
    }
    public function assignedEmployee()
    {
        return $this->hasMany(EmployeeAssignmentLetters::class, 'assigned_employee_id', 'id');
    }
    public function aassigningEmployee()
    {
        return $this->hasMany(EmployeeAssignmentLetters::class, 'assigning_employee_id', 'id');
    }
    public function businessTravelEmployee()
    {
        return $this->hasMany(EmployeeBusinessTravelLetters::class, 'employee_id', 'id');
    }

    public function businessTravelAsFollower()
    {
        return $this->belongsToMany(
            EmployeeBusinessTravelLetters::class,
            'travel_letter_followers',
            'follower_id',
            'travel_letter_id'
        )->withTimestamps();
    }

    public function basicSalary()
    {
        return $this->belongsTo(MasterEmployeeBasicSalary::class, 'basic_salary_id');
    }

    public function grade()
    {
        return $this->hasOneThrough(MasterEmployeeGrade::class, MasterEmployeeBasicSalary::class, 'id', 'id', 'basic_salary_id', 'employee_grade_id');
    }

    public function employeePeriodic()
    {
        return $this->hasMany(EmployeePeriodicSalaryIncrease::class, 'employee_id', 'id');
    }

    public function employeeSignatory()
    {
        return $this->hasMany(EmployeeBusinessTravelLetters::class, 'employee_signatory_id', 'id');
    }
}
