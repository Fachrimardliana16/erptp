<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeGradeSalaryCuts extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_grade_salary_cuts';
    protected $fillable = ['salary_cuts_id', 'grade_id', 'amount', 'desc', 'users_id'];

    public $timestamps = true;

    public function SalaryCuts()
    {
        return $this->belongsTo(MasterEmployeeSalaryCuts::class, 'salary_cuts_id');
    }

    public function gradeCuts()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'grade_id');
    }
}
