<?php

namespace App\Filament\Resources\AssetPurchaseResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\AssetPurchase; // Import the AssetPurchase model
use Carbon\Carbon; // Import Carbon for date manipulation


class PurchaseOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Calculate total purchases
        $totalPurchases = AssetPurchase::count();

        // Calculate total purchases for the current year
        $totalYearlyPurchases = AssetPurchase::whereYear('purchase_date', Carbon::now()->year)->count();

        // Calculate total purchases for the current month
        $totalMonthlyPurchases = AssetPurchase::whereYear('purchase_date', Carbon::now()->year)
            ->whereMonth('purchase_date', Carbon::now()->month)
            ->count();

        return [
            Stat::make('Total Pembelian', $totalPurchases)
                ->description('Total pembelian yang dilakukan sepanjang waktu.')
                ->icon('heroicon-o-shopping-cart') // Contoh ikon dari Heroicons
                ->color('success'), // Warna yang sudah ditentukan seperti 'success', 'danger', dll.

            Stat::make('Total Pembelian Tahun Ini', $totalYearlyPurchases)
                ->description('Total pembelian yang dilakukan tahun ini.')
                ->icon('heroicon-o-calendar') // Contoh ikon dari Heroicons
                ->color('primary'), // Warna yang sudah ditentukan seperti 'primary', 'secondary', dll.

            Stat::make('Total Pembelian Bulan Ini', $totalMonthlyPurchases)
                ->description('Total pembelian yang dilakukan bulan ini.')
                ->icon('heroicon-o-calendar-date-range') // Contoh ikon dari Heroicons
                ->color('warning'), // Warna yang sudah ditentukan seperti 'warning', 'info', dll.
        ];
    }
}
