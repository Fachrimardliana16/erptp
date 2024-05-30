<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeStatusEmployemnt extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'master_employee_status_employement';
    protected $fillable = ['name', 'desc', 'users_id'];

    public $timestamps = true;

    public function employmentStatus()
    {
        return $this->hasMany(Employees::class, 'employment_status_id');
    }
}
