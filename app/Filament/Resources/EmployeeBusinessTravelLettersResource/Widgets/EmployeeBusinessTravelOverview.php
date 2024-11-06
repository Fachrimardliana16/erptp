<?php

namespace App\Filament\Resources\EmployeeBusinessTravelLettersResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\EmployeeBusinessTravelLetters;
use Carbon\Carbon;

class EmployeeBusinessTravelOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $total = EmployeeBusinessTravelLetters::count();
        $totalThisYear = EmployeeBusinessTravelLetters::whereYear('start_date', Carbon::now()->year)->count();
        $totalThisMonth = EmployeeBusinessTravelLetters::whereYear('start_date', Carbon::now()->year)
            ->whereMonth('start_date', Carbon::now()->month)
            ->count();
        $totalToday = EmployeeBusinessTravelLetters::whereDate('start_date', Carbon::today())->count();

        return [
            Stat::make('Total Business Travels', $total)
                ->description('Total keseluruhan perjalanan dinas'),
            Stat::make('Total Business Travels This Year', $totalThisYear)
                ->description('Total perjalanan dinas tahun ini'),
            Stat::make('Total Business Travels This Month', $totalThisMonth)
                ->description('Total perjalanan dinas bulan ini'),
            Stat::make('Total Business Travels Today', $totalToday)
                ->description('Total perjalanan dinas hari ini'),
        ];
    }
}
