<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EmployeeFamilies extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'employee_id',
        'family_id',
        'name',
        'gender',
        'id_number',
        'place_birth',
        'date_birth',
        'users_id',
    ];


    public function employeeFamilies()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function families()
    {
        return $this->belongsTo(MasterEmployeeFamily::class, 'family_id');
    }
}
