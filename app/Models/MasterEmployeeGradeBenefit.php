<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MasterEmployeeGradeBenefit extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_employee_grade_benefit';

    protected $fillable = [
        'benefits',
        'grade_id',
        'desc',
        'users_id'
    ];

    protected $casts = [
        'benefits' => 'array'
    ];

    public function gradeBenefits()
    {
        return $this->belongsTo(MasterEmployeeGrade::class, 'grade_id', 'id');
    }

    public function benefits()
    {
        return $this->hasMany(MasterEmployeeBenefit::class, 'id', 'benefit_id');
    }

    // Method untuk mendapatkan nilai tunjangan spesifik
    public function getBenefitAmount($benefitId)
    {
        if (!is_array($this->benefits)) {
            return 0;
        }

        $benefit = collect($this->benefits)
            ->firstWhere('benefit_id', $benefitId);

        return $benefit ? (int)($benefit['amount'] ?? 0) : 0;
    }

    // Method untuk mendapatkan semua nilai tunjangan
    public function getAllBenefitAmounts()
    {
        if (!is_array($this->benefits)) {
            return collect();
        }

        return collect($this->benefits)->mapWithKeys(function ($benefit) {
            return [
                $benefit['benefit_id'] => (int)($benefit['amount'] ?? 0)
            ];
        });
    }
}
