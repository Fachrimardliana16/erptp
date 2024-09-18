<?php

namespace App\Filament\Widgets;

use App\Models\Employees;
use Filament\Widgets\ChartWidget;

class EmployeesWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pegawai';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Hitung jumlah pegawai berdasarkan employment_status_id
        $pegawaiCount = Employees::where('employment_status_id', '60918c6d-f161-4a7c-a33b-29231dc23013')->count();
        $calonPegawaiCount = Employees::where('employment_status_id', 'b7b5e4a6-e405-498f-bec9-c3989abdd41b')->count();
        $kontrakCount = Employees::where('employment_status_id', '213c345c-22f7-403c-8d84-d93b7a524479')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pegawai',
                    'data' => [
                        $pegawaiCount,
                        $calonPegawaiCount,
                        $kontrakCount,
                    ],
                    'backgroundColor' => [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Pegawai', 'Calon Pegawai', 'Kontrak'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
