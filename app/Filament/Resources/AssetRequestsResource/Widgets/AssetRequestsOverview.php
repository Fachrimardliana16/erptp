<?php

namespace App\Filament\Resources\AssetRequestsResource\Widgets;

use App\Models\AssetRequests;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssetRequestsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Hitung total aset
        $totalRequest = AssetRequests::count();

        // Hitung total aset dengan status_request false
        $totalRequestFalse = AssetRequests::where('status_request', false)->count();

        // Hitung total aset dengan status_request true
        $totalRequestTrue = AssetRequests::where('status_request', true)->count();

        return [
            Stat::make('Total Permaintaan Aset', $totalRequest)
                ->color('primary')
                ->description('Total Semua Aset'),
            Stat::make('Status Belum Selesai', $totalRequestFalse)
                ->color('primary')
                ->description('Total Permintaan Belum Selesai'),
            Stat::make('Status Selesai', $totalRequestTrue)
                ->color('primary')
                ->description('Total Permintaan Selesai'),
        ];
    }
}
