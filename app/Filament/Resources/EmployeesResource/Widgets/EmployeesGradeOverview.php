<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Employees;
use App\Models\MasterEmployeeBasicSalary; // Pastikan model ini ada

class EmployeesGradeOverview extends ChartWidget
{
    protected static ?string $heading = 'Golongan Pegawai Overview';

    protected function getData(): array
    {
        // Ambil semua golongan dari master_employee_basic_salary
        $basicSalaries = MasterEmployeeBasicSalary::all()->pluck('name');

        // Inisialisasi array untuk menyimpan jumlah pegawai berdasarkan golongan
        $basicSalaryCounts = [];

        // Hitung pegawai berdasarkan golongan
        foreach ($basicSalaries as $basicSalary) {
            $basicSalaryCounts[$basicSalary] = Employees::where('basic_salary_id', function ($query) use ($basicSalary) {
                $query->select('id')->from('master_employee_basic_salary')->where('name', $basicSalary);
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
}
