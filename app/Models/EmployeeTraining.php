<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTraining extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'employee_training';
    protected $fillable = [
        'training_date',
        'employee_id',
        'training_title',
        'training_location',
        'organizer',
        'photo_trainings',
        'docs_training',
        'users_id'
    ];

    public function employeeTraining()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }
}
