<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class EmployeeSalary extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'employee_salaries';

    protected $fillable = [
        'employee_id',
        'employee_benefit_id',
        'basic_salary',
        'amount',
        'total_salary',
        'users_id'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'amount' => 'decimal:2',
        'total_salary' => 'decimal:2',
    ];

    // Relasi
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function benefitEmployee()
    {
        return $this->belongsTo(EmployeeBenefit::class, 'employee_benefit_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    // Scopes
    public function scopeWithComplete($query)
    {
        return $query->with([
            'employee.basicSalary',
            'employee.grade',
            'benefitEmployee'
        ]);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('employee', function ($q) {
            $q->where('status', 'active');
        });
    }

    // Methods
    public function calculateSalary()
    {
        if (!$this->employee) {
            return [
                'basic_salary' => 0,
                'benefits' => 0,
                'total' => 0
            ];
        }

        $basicSalary = $this->employee->basicSalary?->amount ?? 0;
        $totalBenefits = $this->benefitEmployee?->getTotalAmount() ?? 0;

        return [
            'basic_salary' => $basicSalary,
            'benefits' => $totalBenefits,
            'total' => $basicSalary + $totalBenefits
        ];
    }

    public function getFormattedBasicSalary()
    {
        return number_format($this->basic_salary, 2, ',', '.');
    }

    public function getFormattedAmount()
    {
        return number_format($this->amount, 2, ',', '.');
    }

    public function getFormattedTotalSalary()
    {
        return number_format($this->total_salary, 2, ',', '.');
    }

    // Mutators & Accessors
    protected function setBasicSalaryAttribute($value)
    {
        $this->attributes['basic_salary'] = str_replace(',', '.', $value);
    }

    protected function setAmountAttribute($value)
    {
        $this->attributes['amount'] = str_replace(',', '.', $value);
    }

    protected function setTotalSalaryAttribute($value)
    {
        $this->attributes['total_salary'] = str_replace(',', '.', $value);
    }

    // Boot Method
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->employee) {
                // Set basic salary
                $model->basic_salary = $model->employee->basicSalary?->amount ?? 0;

                // Calculate total benefits
                if ($model->benefitEmployee) {
                    $model->amount = $model->benefitEmployee->getTotalAmount();
                }

                // Calculate total salary
                $model->total_salary = $model->basic_salary + $model->amount;
            }

            // Validate data
            if ($model->basic_salary < 0 || $model->amount < 0) {
                return false;
            }
        });

        // Set default values for new records
        static::creating(function ($model) {
            if (!$model->users_id) {
                $model->users_id = auth()->id();
            }
        });
    }

    // Query Helpers
    public static function findByEmployee($employeeId)
    {
        return static::where('employee_id', $employeeId)
            ->withComplete()
            ->latest()
            ->first();
    }

    public static function getLatestByGrade($gradeId)
    {
        return static::whereHas('employee', function ($q) use ($gradeId) {
            $q->where('employee_grade_id', $gradeId);
        })
            ->withComplete()
            ->latest()
            ->get();
    }

    public static function getTotalSalaryByDepartment($departmentId)
    {
        return static::whereHas('employee', function ($q) use ($departmentId) {
            $q->where('departments_id', $departmentId);
        })
            ->sum('total_salary');
    }

    public function validate()
    {
        if (!$this->employee_id || !$this->employee_benefit_id) {
            return false;
        }

        if ($this->basic_salary < 0 || $this->amount < 0) {
            return false;
        }

        if (!$this->employee || !$this->benefitEmployee) {
            return false;
        }

        return true;
    }

    public function getBenefitAmount($identifier)
    {
        if (!$this->employee?->grade || !is_array($this->benefits)) {
            return 0;
        }

        // Map identifier ke benefit_id sesuai database
        $benefitMap = [
            'beras' => '1', // Sesuaikan dengan ID di database
            'jabatan' => '2',
            'kesehatan' => '3',
            'dplk' => '4',
            'tkk' => '5',
            'keluarga' => '6',
            'air' => '7'
        ];

        $benefitId = $benefitMap[$identifier] ?? $identifier;

        // Cek apakah benefit ada
        $hasBenefit = collect($this->benefits)
            ->pluck('benefit_id')
            ->contains($benefitId);

        if (!$hasBenefit) {
            return 0;
        }

        $gradeBenefit = MasterEmployeeGradeBenefit::where('grade_id', $this->employee->grade->id)
            ->first();

        if (!$gradeBenefit || !is_array($gradeBenefit->benefits)) {
            return 0;
        }

        $benefit = collect($gradeBenefit->benefits)
            ->firstWhere('benefit_id', $benefitId);

        return $benefit ? (int)($benefit['amount'] ?? 0) : 0;
    }
}
