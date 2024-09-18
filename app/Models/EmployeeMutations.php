<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeeMutations extends Model
{

    use HasFactory, HasUuids;

    protected $table = 'employee_mutations';
    protected $fillable = [
        'decision_letter_number',
        'mutation_date',
        'employee_id',
        'old_department_id',
        'old_sub_department_id',
        'new_department_id',
        'new_sub_department_id',
        'old_position_id',
        'new_position_id',
        'docs',
        'users_id',
    ];

    public function employeeMutation()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function oldDepartment()
    {
        return $this->belongsTo(MasterDepartments::class, 'old_department_id', 'id');
    }

    public function oldSubDepartment()
    {
        return $this->belongsTo(MasterSubDepartments::class, 'old_sub_department_id', 'id');
    }

    public function newDepartment()
    {
        return $this->belongsTo(MasterDepartments::class, 'new_department_id', 'id');
    }

    public function newSubDepartment()
    {
        return $this->belongsTo(MasterSubDepartments::class, 'new_sub_department_id', 'id');
    }

    public function oldPosition()
    {
        return $this->belongsTo(MasterEmployeePosition::class, 'old_position_id', 'id');
    }

    public function newPosition()
    {
        return $this->belongsTo(MasterEmployeePosition::class, 'new_position_id', 'id');
    }
}
