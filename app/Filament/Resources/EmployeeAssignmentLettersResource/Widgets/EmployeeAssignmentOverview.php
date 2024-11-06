<?php

namespace App\Filament\Resources\EmployeeAssignmentLettersResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\EmployeeAssignmentLetters;
use Carbon\Carbon;

class EmployeeAssignmentOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $total = EmployeeAssignmentLetters::count();
        $totalThisYear = EmployeeAssignmentLetters::whereYear('start_date', Carbon::now()->year)->count();
        $totalThisMonth = EmployeeAssignmentLetters::whereYear('start_date', Carbon::now()->year)
            ->whereMonth('start_date', Carbon::now()->month)
            ->count();
        $totalToday = EmployeeAssignmentLetters::whereDate('start_date', Carbon::today())->count();

        return [
            Stat::make('Total Assignments', $total)
                ->description('Total keseluruhan surat tugas'),
            Stat::make('Total Assignments This Year', $totalThisYear)
                ->description('Total surat tugas tahun ini'),
            Stat::make('Total Assignments This Month', $totalThisMonth)
                ->description('Total surat tugas bulan ini'),
            Stat::make('Total Assignments Today', $totalToday)
                ->description('Total surat tugas hari ini'),
        ];
    }
}
