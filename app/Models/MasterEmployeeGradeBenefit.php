<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeGradeBenefit extends Model
{
    use HasUuids;

    protected $table = 'master_employee_grade_benefit';

    protected $fillable = [
        'grade_id',
        'benefits',
        'desc',
        'users_id'
    ];

    protected $casts = [
        'benefits' => 'array'
    ];

    public function grade()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'grade_id');
    }


    public function getBenefitAmount($benefitId)
    {
        if (!is_array($this->benefits)) return 0;

        $benefit = collect($this->benefits)
            ->firstWhere('benefit_id', $benefitId);

        return $benefit ? (float)($benefit['amount'] ?? 0) : 0;
    }

    public function getAllBenefitAmounts()
    {
        if (!is_array($this->benefits)) return collect();

        return collect($this->benefits)->mapWithKeys(function ($benefit) {
            return [$benefit['benefit_id'] => (float)($benefit['amount'] ?? 0)];
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            return $model->validateBenefits();
        });
    }

    public function validateBenefits()
    {
        if (!is_array($this->benefits)) return false;

        foreach ($this->benefits as $benefit) {
            if (!isset($benefit['benefit_id'], $benefit['amount'])) return false;
            if (!is_numeric($benefit['amount']) || $benefit['amount'] < 0) return false;
            if (!MasterEmployeeBenefit::where('id', $benefit['benefit_id'])
                ->where('status', 'active')
                ->exists()) {
                return false;
            }
        }
        return true;
    }
}
