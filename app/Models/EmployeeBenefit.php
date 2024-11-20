<?php
// App/Models/EmployeeBenefit.php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeBenefit extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'employee_benefits';
    protected $keyType = 'string';

    protected $fillable = [
        'employee_id',
        'benefits',
        'users_id'
    ];

    protected $casts = [
        'benefits' => 'array'
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    // Method untuk mendapatkan nilai tunjangan spesifik
    public function getBenefitAmount($benefitId)
    {
        if (!$this->employee || !$this->employee->grade || !is_array($this->benefits)) {
            return 0;
        }

        // Cek apakah pegawai memiliki tunjangan ini
        $hasBenefit = collect($this->benefits)->pluck('benefit_id')->contains($benefitId);
        if (!$hasBenefit) {
            return 0;
        }

        // Ambil nilai tunjangan dari grade pegawai
        $gradeBenefit = MasterEmployeeGradeBenefit::where('grade_id', $this->employee->grade->id)->first();
        if (!$gradeBenefit || !is_array($gradeBenefit->benefits)) {
            return 0;
        }

        // Cari nilai tunjangan yang sesuai
        $benefit = collect($gradeBenefit->benefits)
            ->firstWhere('benefit_id', $benefitId);

        return $benefit ? (int)($benefit['amount'] ?? 0) : 0;
    }

    // Method untuk mendapatkan total semua tunjangan
    public function getTotalAmount()
    {
        if (!$this->employee || !$this->employee->grade || !is_array($this->benefits)) {
            return 0;
        }

        $gradeBenefit = MasterEmployeeGradeBenefit::where('grade_id', $this->employee->grade->id)->first();
        if (!$gradeBenefit || !is_array($gradeBenefit->benefits)) {
            return 0;
        }

        $total = 0;
        foreach ($this->benefits as $employeeBenefit) {
            $benefitId = $employeeBenefit['benefit_id'] ?? null;
            if (!$benefitId) continue;

            $gradeBenefitValue = collect($gradeBenefit->benefits)
                ->firstWhere('benefit_id', $benefitId);

            if ($gradeBenefitValue) {
                $total += (int)($gradeBenefitValue['amount'] ?? 0);
            }
        }

        return $total;
    }
}
