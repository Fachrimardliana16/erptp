<?php

namespace App\Filament\Resources\EmployeeAgreementResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\EmployeeAgreement;
use Carbon\Carbon;

class AgreementOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $total = EmployeeAgreement::count();
        $totalThisYear = EmployeeAgreement::whereYear('agreement_date_start', Carbon::now()->year)->count();
        $totalThisMonth = EmployeeAgreement::whereYear('agreement_date_start', Carbon::now()->year)
            ->whereMonth('agreement_date_start', Carbon::now()->month)
            ->count();

        return [
            Stat::make('Total Agreements', $total)
                ->description('Total keseluruhan perjanjian'),
            Stat::make('Total Agreements This Year', $totalThisYear)
                ->description('Total perjanjian tahun ini'),
            Stat::make('Total Agreements This Month', $totalThisMonth)
                ->description('Total perjanjian bulan ini'),
        ];
    }
}
