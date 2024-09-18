<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeAgreement extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_agreement';
    protected $fillable = ['name', 'desc', 'users_id',];

    public $timestamps = true;

    public function employeeAgreement()
    {
        return $this->hasMany(Employees::class, 'employee_agreement_id');
    }

    public function agreement()
    {
        return $this->hasMany(EmployeeAgreement::class, 'agreement_id', 'id');
    }
}
