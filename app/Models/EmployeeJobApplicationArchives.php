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
        'place_of_birth',
        'date_of_birth',
        'email',
        'contact',
        'religion',
        'education',
        'major',
        'archive_file',
        'notes',
        'users_id'
    ];
}
