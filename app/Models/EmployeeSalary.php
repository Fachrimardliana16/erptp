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
