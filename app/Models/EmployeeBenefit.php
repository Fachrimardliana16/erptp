<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeBenefit extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'employee_benefits';

    protected $fillable = [
        'employee_id',
        'benefits',
        'users_id'
    ];

    protected $casts = [
        'benefits' => 'array'
    ];

    // Relasi
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class, 'employee_benefit_id');
    }

    // Method untuk mendapatkan nilai tunjangan spesifik
    public function getBenefitAmount($benefitId)
    {
        $benefits = is_array($this->benefits) ? $this->benefits : json_decode($this->benefits, true);

        foreach ($benefits as $benefit) {
            if ($benefit['benefit_id'] === $benefitId) {
                return (float)($benefit['amount'] ?? 0);
            }
        }

        return 0;
    }

    // Method untuk mendapatkan detail semua tunjangan
    public function getBenefitDetails()
    {
        if (!is_array($this->benefits)) {
            return collect([]);
        }

        return collect($this->benefits)->map(function ($benefit) {
            $masterBenefit = MasterEmployeeBenefit::find($benefit['benefit_id']);
            return [
                'id' => $benefit['benefit_id'],
                'name' => $masterBenefit?->name ?? 'Unknown',
                'amount' => (float) $benefit['amount']
            ];
        });
    }

    // Method untuk mendapatkan semua nilai tunjangan
    public function getAllBenefitAmounts()
    {
        if (!$this->employee?->grade || !is_array($this->benefits)) {
            return collect();
        }

        return collect($this->benefits)->mapWithKeys(function ($benefit) {
            $benefitId = $benefit['benefit_id'] ?? null;
            if (!$benefitId) return [];

            return [$benefitId => $this->getBenefitAmount($benefitId)];
        });
    }

    // Method untuk mendapatkan total semua tunjangan
    public function getTotalAmount()
    {
        return $this->getAllBenefitAmounts()->sum();
    }

    // Method untuk validasi benefits
    public function validateBenefits()
    {
        if (!is_array($this->benefits)) {
            return false;
        }

        foreach ($this->benefits as $benefit) {
            if (!isset($benefit['benefit_id'])) {
                return false;
            }

            if (!MasterEmployeeBenefit::where('id', $benefit['benefit_id'])
                ->where('status', 'active')
                ->exists()) {
                return false;
            }
        }

        return true;
    }

    // Observer events
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->validateBenefits()) {
                return false;
            }
        });
    }

    public function getBenefitLabel(): string
    {
        $benefitCount = count($this->benefits ?? []);
        return "Tunjangan ({$benefitCount} item) - " . $this->created_at->format('d M Y');
    }

    public function hasBenefit($masterBenefitId): bool
    {
        if (!is_array($this->benefits)) return false;

        return collect($this->benefits)
            ->pluck('benefit_id')
            ->contains($masterBenefitId);
    }
}
