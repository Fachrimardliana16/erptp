<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use App\Models\Employees;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeesOverview extends BaseWidget
{
    protected static ?int $sort = 2;
    protected function getStats(): array
    {
        // Employee counts by status
        $pegawaiTetap = Employees::whereIn('employment_status_id', function ($query) {
            $query->select('id')->from('master_employee_status_employement')->where('name', 'LIKE', '%Pegawai%');
        })->count();

        $calonPegawai = Employees::whereIn('employment_status_id', function ($query) {
            $query->select('id')->from('master_employee_status_employement')->where('name', 'LIKE', '%Calon Pegawai%');
        })->count();

        $pegawaiKontrak = Employees::whereIn('employment_status_id', function ($query) {
            $query->select('id')->from('master_employee_status_employement')->where('name', 'LIKE', '%Kontrak%');
        })->count();

        $pegawaiTHL = Employees::whereIn('employment_status_id', function ($query) {
            $query->select('id')->from('master_employee_status_employement')->where('name', 'LIKE', '%Tenaga Harian Lepas%');
        })->count();

        return [
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
        ];
    }
}
