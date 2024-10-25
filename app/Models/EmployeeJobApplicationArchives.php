<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeeJobApplicationArchives extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'employee_job_application_archives';
    protected $fillable = [
        'registration_number',
        'registration_date',
        'name',
        'address',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'email',
        'contact',
        'religion',
        'employee_education_id',
        'major',
        'archive_file',
        'notes',
        'application_status',
        'users_id'
    ];

    public function agreementJob()
    {
        return $this->hasMany(EmployeeAgreement::class, 'job_application_archives_id', 'id');
    }
    public function employeedu()
    {
        return $this->belongsTo(MasterEmployeeEducation::class, 'employee_education_id', 'id');
    }
}
