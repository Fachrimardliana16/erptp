<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Employees;

class EmployeesChartOverview extends ChartWidget
{
    protected static ?string $heading = 'Status Employee Chart';

    protected function getOptions(): array
    {
        return [
            'collapsible' => true,
            'collapsed' => false, // Default state
        ];
    }

    protected function getData(): array
    {
        $totalEmployees = Employees::count();

        $pegawaiTetap = Employees::where('employment_status_id', function ($query) {
            $query->select('id')->from('master_employee_status_employement')->where('name', 'Pegawai');
        })->count();

        $calonPegawai = Employees::where('employment_status_id', function ($query) {
            $query->select('id')->from('master_employee_status_employement')->where('name', 'Calon Pegawai');
        })->count();

        $pegawaiKontrak = Employees::where('employment_status_id', function ($query) {
            $query->select('id')->from('master_employee_status_employement')->where('name', 'Kontrak');
        })->count();

        $pegawaiTHL = Employees::where('employment_status_id', function ($query) {
            $query->select('id')->from('master_employee_status_employement')->where('name', 'Tenaga Harian Lepas');
        })->count();

        return [
            'labels' => [
                'Pegawai Tetap',
                'Calon Pegawai',
                'Kontrak',
                'Tenaga Harian Lepas',
            ],
            'datasets' => [
                [
                    'label' => 'Employees Overview',
                    'data' => [
                        $pegawaiTetap,
                        $calonPegawai,
                        $pegawaiKontrak,
                        $pegawaiTHL,
                    ],
                    'backgroundColor' => [
                        '#4caf50',
                        '#2196f3',
                        '#ff9800',
                        '#f44336',
                    ],
                ],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'height' => 150,
                'width' => 150,
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'bottom',
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
