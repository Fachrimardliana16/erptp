<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeFamily extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_family';
    protected $fillable = ['name', 'desc', 'users_id'];

    public $timestamps = true;

    public function families()
    {
        return $this->hasMany(EmployeeFamilies::class, 'family_id');
    }
}
