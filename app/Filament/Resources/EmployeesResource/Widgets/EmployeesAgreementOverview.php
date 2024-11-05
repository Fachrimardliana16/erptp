<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Forms\Components\Select;
use App\Models\Employees;


class EmployeesAgreementOverview extends BaseWidget
{

    protected function getStats(): array
    {
        $query = Employees::query();

        $totalEmployees = $query->count();
        $pkwtCount = Employees::whereIn('master_employee_agreement_id', function ($query) {
            $query->select('id')->from('master_employee_agreement')->where('name', 'LIKE', '%[PKWT] Perjanjian Kerja Waktu Tertentu%');
        })->count();

        $pkwttCount = Employees::whereIn('master_employee_agreement_id', function ($query) {
            $query->select('id')->from('master_employee_agreement')->where('name', 'LIKE', '%[PKWTT] Perjanjian Kerja Waktu Tidak Tertentu%');
        })->count();

        return [
            Stat::make('Total Karyawan', $totalEmployees),
            Stat::make('[PKWT] Perjanjian Kerja Waktu Tertentu', $pkwtCount),
            Stat::make('[PKWTT] Perjanjian Kerja Waktu Tidak Tertentu', $pkwttCount),
        ];
    }
}
