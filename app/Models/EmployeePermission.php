<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePermission extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'employee_permission';
    protected $fillable = [
        'start_permission_date',
        'end_permission_date',
        'employee_id',
        'permission_id',
        'permission_desc',
        'scan_doc',
        'users_id',
    ];

    public function employeePermission()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function permission()
    {
        return $this->belongsTo(MasterEmployeePermission::class, 'permission_id', 'id');
    }
}
