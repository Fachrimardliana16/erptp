<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeePermission extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_permission';
    protected $fillable = ['name', 'desc', 'users_id'];

    public $timestamps = true;

    public function permission()
    {
        return $this->hasMany(EmployeePermission::class, 'permission_id', 'id');
    }
}
