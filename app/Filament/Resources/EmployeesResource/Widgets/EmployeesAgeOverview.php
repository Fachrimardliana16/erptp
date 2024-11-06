<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Employees;

class EmployeesAgeOverview extends ChartWidget
{
    protected static ?string $heading = 'Chart Umur Karyawan';

    protected function getData(): array
    {
        // Define age ranges
        $ageRanges = [
            '20-29' => 0,
            '30-39' => 0,
            '40-49' => 0,
            '50-59' => 0,
        ];

        // Get all employees
        $employees = Employees::all();

        // Count employees in each age range
        foreach ($employees as $employee) {
            $age = $this->calculateAge($employee->date_birth);
            if ($age >= 20 && $age < 30) {
                $ageRanges['20-29']++;
            } elseif ($age >= 30 && $age < 40) {
                $ageRanges['30-39']++;
            } elseif ($age >= 40 && $age < 50) {
                $ageRanges['40-49']++;
            } elseif ($age >= 50 && $age < 60) {
                $ageRanges['50-56']++;
            }
        }

        return [
            'labels' => array_keys($ageRanges), // Age ranges as labels
            'datasets' => [
                [
                    'label' => 'Number of Employees',
                    'data' => array_values($ageRanges), // Count of employees in each age range
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Chart type
    }

    private function calculateAge($dateOfBirth)
    {
        return now()->diffInYears($dateOfBirth); // Calculate age based on date of birth
    }

    protected static ?string $maxHeight = '270px';

    protected static ?array $options = [
        'scales' => [
            'y' => [
                'grid' => [
                    'display' => false,
                ],
                'ticks' => [
                    'display' => false,
                ],
            ],
            'x' => [
                'grid' => [
                    'display' => false,
                ],
                'ticks' => [
                    'display' => false,
                ],
            ],
        ],
    ];
}
