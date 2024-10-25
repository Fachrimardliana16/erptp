<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Employees;

class EmployeesAgreementChartOverview extends ChartWidget
{
    protected static ?string $heading = 'Perjanjian Karyawan';

    protected function getData(): array
    {
        // Count employees based on agreement
        $pkwtCount = Employees::where('employee_agreement_id', function ($query) {
            $query->select('id')->from('master_employee_agreement')->where('name', '[PKWT] Perjanjian Kerja Waktu Tertentu');
        })->count();

        $pkwttCount = Employees::where('employee_agreement_id', function ($query) {
            $query->select('id')->from('master_employee_agreement')->where('name', '[PKWTT] Perjanjian Kerja Waktu Tidak Tertentu');
        })->count();

        return [
            'labels' => [
                '[PKWT] Perjanjian Kerja Waktu Tertentu',
                '[PKWTT] Perjanjian Kerja Waktu Tidak Tertentu',
            ],
            'datasets' => [
                [
                    'label' => 'Perjanjian Karyawan',
                    'data' => [$pkwtCount, $pkwttCount],
                    'backgroundColor' => ['#FF6384', '#36A2EB'],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
