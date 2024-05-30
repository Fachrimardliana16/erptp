<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeEducation extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_education';
    protected $fillable = ['name', 'desc', 'users_id'];

    public $timestamps = true;

    public function employeeEducation()
    {
        return $this->hasMany(Employees::class, 'employee_education_id');
    }
}
