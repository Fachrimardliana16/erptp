<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Employees;

class EmployeesGradeOverview extends ChartWidget
{
    protected static ?string $heading = 'Grading Overview';

    protected function getData(): array
    {
        // Count employees based on grade
        $a1Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'A1');
        })->count();

        $a2Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'A2');
        })->count();

        $a3Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'A3');
        })->count();

        $a4Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'A4');
        })->count();

        $b1Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'B1');
        })->count();

        $b2Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'B2');
        })->count();

        $b3Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'B3');
        })->count();

        $b4Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'B4');
        })->count();

        $c1Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'C1');
        })->count();

        $c2Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'C2');
        })->count();

        $c3Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'C3');
        })->count();

        $c4Count = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'C4');
        })->count();

        $kontrakCount = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'Kontrak');
        })->count();

        $thlCount = Employees::where('employee_grade_id', function ($query) {
            $query->select('id')->from('master_employee_grade')->where('name', 'Tenaga Harian Lepas');
        })->count();

        return [
            'labels' => [
                'A1',
                'A2',
                'A3',
                'A4',
                'B1',
                'B2',
                'B3',
                'B4',
                'C1',
                'C2',
                'C3',
                'C4',
                'Kontrak',
                'Tenaga Harian Lepas'
            ],
            'datasets' => [
                [
                    'label' => 'Grade Chart',
                    'data' => [
                        $a1Count,
                        $a2Count,
                        $a3Count,
                        $a4Count,
                        $b1Count,
                        $b2Count,
                        $b3Count,
                        $b4Count,
                        $c1Count,
                        $c2Count,
                        $c3Count,
                        $c4Count,
                        $kontrakCount,
                        $thlCount
                    ],
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF6384',
                        '#36A2EB',
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
