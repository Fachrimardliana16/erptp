<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeeAssignmentLetters extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'employee_assignment_letters';
    protected $fillable = [
        'registration_number',
        'assigning_employee_id',
        'employee_position_id',
        'assigned_employee_id',
        'task',
        'start_date',
        'end_date',
        'description',
        'users_id'
    ];

    public function assignedEmployee()
    {
        return $this->belongsTo(Employees::class, 'assigned_employee_id', 'id');
    }
    public function aassigningEmployee()
    {
        return $this->belongsTo(Employees::class, 'assigning_employee_id', 'id');
    }

    public function positionAssign()
    {
        return $this->belongsTo(MasterEmployeePosition::class, 'employee_position_id', 'id');
    }
}
