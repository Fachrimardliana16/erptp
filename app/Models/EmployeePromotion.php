<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePromotion extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $table = 'employee_promotion';

    protected $fillable = [
        'decision_letter_number',
        'promotion_date',
        'employee_id',
        'old_basic_salary_id',
        'new_basic_salary_id',
        'doc_promotion',
        'desc',
        'users_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function oldBasicSalary()
    {
        return $this->belongsTo(MasterEmployeeBasicSalary::class, 'old_basic_salary_id', 'id');
    }

    public function newBasicSalary()
    {
        return $this->belongsTo(MasterEmployeeBasicSalary::class, 'new_basic_salary_id', 'id');
    }

    // Tambahkan relasi untuk mengakses grade melalui basic salary
    public function oldGrade()
    {
        return $this->hasOneThrough(
            MasterEmployeeGrade::class,
            MasterEmployeeBasicSalary::class,
            'id', // Foreign key di MasterEmployeeBasicSalary
            'id', // Foreign key di MasterEmployeeGrade
            'old_basic_salary_id', // Local key di EmployeePromotion
            'employee_grade_id' // Local key di MasterEmployeeBasicSalary
        );
    }

    public function newGrade()
    {
        return $this->hasOneThrough(
            MasterEmployeeGrade::class,
            MasterEmployeeBasicSalary::class,
            'id', // Foreign key di MasterEmployeeBasicSalary
            'id', // Foreign key di MasterEmployeeGrade
            'new_basic_salary_id', // Local key di EmployeePromotion
            'employee_grade_id' // Local key di MasterEmployeeBasicSalary
        );
    }
}
