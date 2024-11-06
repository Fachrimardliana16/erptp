<?php

namespace App\Filament\Resources\EmployeeTrainingResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\EmployeeTraining;
use Carbon\Carbon;

class TrainingOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $total = EmployeeTraining::count();
        $totalThisYear = EmployeeTraining::whereYear('training_date', Carbon::now()->year)->count();
        $totalThisMonth = EmployeeTraining::whereYear('training_date', Carbon::now()->year)
            ->whereMonth('training_date', Carbon::now()->month)
            ->count();
        $totalToday = EmployeeTraining::whereDate('training_date', Carbon::today())->count();

        return [
            Stat::make('Total Pelatihan', $total)
                ->description('Total keseluruhan pelatihan'),
            Stat::make('Total Pelatihan Pertahun', $totalThisYear)
                ->description('Total pelatihan tahun ini'),
            Stat::make('Total Pelatihan Perbulan', $totalThisMonth)
                ->description('Total pelatihan bulan ini'),
        ];
    }
}
