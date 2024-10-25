<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use App\Models\Employees;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeesOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $totalEmployees = Employees::count();

        // Employee counts by status
        $pegawaiTetap = Employees::where('employment_status_id', function ($query) {
            $query->select('id')->from('master_employee_status_employement')->where('name', 'Pegawai Tetap');
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

        // Employee counts by gender
        $pegawaiLakiLaki = Employees::where('gender', 'Laki-Laki')->count();
        $pegawaiPerempuan = Employees::where('gender', 'Perempuan')->count();

        return [
            Stat::make('Total Karyawan', $totalEmployees)
                ->color('primary')
                ->description('Total Semua Karyawan'),
            Stat::make('Pegawai Tetap', $pegawaiTetap)
                ->color('primary')
                ->description('Total Status : Pegawai Tetap'),
            Stat::make('Calon Pegawai', $calonPegawai)
                ->color('primary')
                ->description('Total Status : Calon Pegawai'),
            Stat::make('Kontrak', $pegawaiKontrak)
                ->color('primary')
                ->description('Total Status : Kontrak'),
            Stat::make('Tenaga Harian Lepas', $pegawaiTHL)
                ->color('primary')
                ->description('Total Status : Tenaga Harian Lepas'),
            Stat::make('Karyawana Laki-Laki', $pegawaiLakiLaki)
                ->color('primary')
                ->description('Total Gender : Laki-Laki'),
            Stat::make('Karyawan Perempuan', $pegawaiPerempuan)
                ->color('primary')
                ->description('Total Gender : Perempuan'),
        ];
    }
}
