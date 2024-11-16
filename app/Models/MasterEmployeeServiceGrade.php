<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MasterEmployeeServiceGrade extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_service_grade';

    protected $fillable = [
        'employee_grade_id',
        'service_grade',
        'users_id'
    ];

    public function employeeGrade()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'employee_grade_id');
    }

    public function basicSalaries()
    {
        return $this->hasMany(MasterEmployeeBasicSalary::class, 'employee_service_grade_id');
    }
}
