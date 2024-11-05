<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSalary extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'employee_salaries';

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'benefits_1',
        'benefits_2',
        'benefits_3',
        'benefits_4',
        'benefits_5',
        'benefits_6',
        'benefits_7',
        'benefits_8',
        'amount',
        'users_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    // public function basicSalary()
    // {
    //     return $this->belongsTo(MasterEmployeeBasicSalary::class, 'basic_salary_id');
    // }

    // public function benefit1()
    // {
    //     return $this->belongsTo(MasterEmployeeGradeBenefit::class, 'benefitsid_1');
    // }

    // public function benefit2()
    // {
    //     return $this->belongsTo(MasterEmployeeGradeBenefit::class, 'benefitsid_2');
    // }

    // public function benefit3()
    // {
    //     return $this->belongsTo(MasterEmployeeGradeBenefit::class, 'benefitsid_3');
    // }

    // public function benefit4()
    // {
    //     return $this->belongsTo(MasterEmployeeGradeBenefit::class, 'benefitsid_4');
    // }

    // public function benefit5()
    // {
    //     return $this->belongsTo(MasterEmployeeGradeBenefit::class, 'benefitsid_5');
    // }

    // public function benefit6()
    // {
    //     return $this->belongsTo(MasterEmployeeGradeBenefit::class, 'benefitsid_6');
    // }

    // public function benefit7()
    // {
    //     return $this->belongsTo(MasterEmployeeGradeBenefit::class, 'benefitsid_7');
    // }

    // public function benefit8()
    // {
    //     return $this->belongsTo(MasterEmployeeGradeBenefit::class, 'benefitsid_8');
    // }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->amount = $model->basic_salary + $model->benefits_1 + $model->benefits_2 + $model->benefits_3 +
                $model->benefits_4 + $model->benefits_5 + $model->benefits_6 + $model->benefits_7 +
                $model->benefits_8 + $model->Rounding + $model->Incentive + $model->Backpay;
        });
    }
}
