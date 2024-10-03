<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeePosition extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_position';
    protected $fillable = ['name', 'desc', 'users_id'];

    public $timestamps = true;

    public function employeePosition()
    {
        return $this->hasMany(Employees::class, 'employee_position_id');
    }

    public function oldPosition()
    {
        return $this->hasMany(EmployeeMutations::class, 'old_position_id', 'id');
    }

    public function newPosition()
    {
        return $this->hasMany(EmployeeMutations::class, 'new_position_id', 'id');
    }

    public function agreementPosition()
    {
        return $this->hasMany(EmployeeAgreement::class, 'employee_position_id', 'id');
    }

    public function positionAssign()
    {
        return $this->hasMany(EmployeeAssignmentLetters::class, 'employee_position_id', 'id');
    }
}
