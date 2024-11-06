<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Employees;
use App\Models\MasterEmployeeBasicSalary; // Pastikan model ini ada
use App\Models\MasterEmployeeGrade;

class EmployeesGradeOverview extends ChartWidget
{
    protected static ?string $heading = 'Chart Golongan Pegawai';

    protected function getData(): array
    {
        // Ambil semua golongan dari master_employee_basic_salary
        $gradeEmployee = MasterEmployeeGrade::all()->pluck('name');

        // Inisialisasi array untuk menyimpan jumlah pegawai berdasarkan golongan
        $basicSalaryCounts = [];

        // Hitung pegawai berdasarkan golongan
        foreach ($gradeEmployee as $grade) {
            $basicSalaryCounts[$grade] = Employees::where('employee_grade_id', function ($query) use ($grade) {
                $query->select('id')->from('master_employee_grade')->where('name', $grade);
            })->count();
        }

        return [
            'labels' => array_keys($basicSalaryCounts), // Ambil nama-nama golongan sebagai label
            'datasets' => [
                [
                    'label' => 'Golongan Chart',
                    'data' => array_values($basicSalaryCounts), // Ambil jumlah pegawai sebagai data
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
        return 'doughnut'; // Tipe chart yang digunakan
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
