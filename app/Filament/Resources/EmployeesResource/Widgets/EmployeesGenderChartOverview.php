<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use App\Models\Employees;
use Filament\Widgets\ChartWidget;

class EmployeesGenderChartOverview extends ChartWidget
{
    protected static ?string $heading = 'Gender Chart';

    protected function getData(): array
    {
        $totalEmployees = Employees::count();

        $pegawaiLakiLaki = Employees::where('gender', 'Laki-Laki')->count();
        $pegawaiPerempuan = Employees::where('gender', 'Perempuan')->count();

        return [
            'labels' => [
                'Laki-Laki',
                'Perempuan',
            ],
            'datasets' => [
                [
                    'label' => 'Employees Overview',
                    'data' => [
                        $pegawaiLakiLaki,
                        $pegawaiPerempuan,
                    ],
                    'backgroundColor' => [
                        '#9c27b0',
                        '#3f51b5',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
