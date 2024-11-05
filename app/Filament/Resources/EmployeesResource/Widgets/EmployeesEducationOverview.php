<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Employees;

class EmployeesEducationOverview extends ChartWidget
{
    protected static ?string $heading = 'Pendidikan Karyawan';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Count employees based on education level
        $sdCount = Employees::where('employee_education_id', function ($query) {
            $query->select('id')->from('master_employee_education')->where('name', 'SD');
        })->count();

        $smpCount = Employees::where('employee_education_id', function ($query) {
            $query->select('id')->from('master_employee_education')->where('name', 'SMP');
        })->count();

        $smaCount = Employees::where('employee_education_id', function ($query) {
            $query->select('id')->from('master_employee_education')->where('name', 'SMA');
        })->count();

        $d1Count = Employees::where('employee_education_id', function ($query) {
            $query->select('id')->from('master_employee_education')->where('name', 'D1');
        })->count();

        $d3Count = Employees::where('employee_education_id', function ($query) {
            $query->select('id')->from('master_employee_education')->where('name', 'D3');
        })->count();

        $d4Count = Employees::where('employee_education_id', function ($query) {
            $query->select('id')->from('master_employee_education')->where('name', 'D4');
        })->count();

        $s1Count = Employees::where('employee_education_id', function ($query) {
            $query->select('id')->from('master_employee_education')->where('name', 'S1');
        })->count();

        $s2Count = Employees::where('employee_education_id', function ($query) {
            $query->select('id')->from('master_employee_education')->where('name', 'S2');
        })->count();

        return [
            'labels' => ['SD', 'SMP', 'SMA', 'D1', 'D3', 'D4', 'S1', 'S2'],
            'datasets' => [
                [
                    'label' => 'Education Chart',
                    'data' => [$sdCount, $smpCount, $smaCount, $d1Count, $d3Count, $d4Count, $s1Count, $s2Count],
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF6384',
                        '#36A2EB'
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected static ?string $maxHeight = '270px';
}
