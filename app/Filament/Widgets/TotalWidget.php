<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use App\Models\Employees;
use App\Models\MoneyVoucherReturns;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class TotalWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalAssets = Asset::count();
        $totalEmployees = Employees::count();

        $totalAmount = MoneyVoucherReturns::whereMonth('date_voucher_returns', Carbon::now()->month)
            ->whereYear('date_voucher_returns', Carbon::now()->year)
            ->sum('total_amount');

        // Memformat jumlah dengan kata "Rp."
        $formattedTotalAmount = 'Rp. ' . number_format($totalAmount, 0, ',', '.');
        return [
            Stat::make('Total Aset', $totalAssets)
                ->color('primary')
                ->description('Total Semua Aset'),
            Stat::make('Total Pegawai', $totalEmployees)
                ->color('primary')
                ->description('Total Karyawan'),
            Stat::make('Total Pengeluaran Bulan Ini', $formattedTotalAmount)
                ->color('primary')
                ->description('Total Pengeluaran Kas Kecil Yang Selesai'),
        ];
    }
}
