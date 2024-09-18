<?php

namespace App\Filament\Resources\AssetResource\Widgets;

use App\Models\Asset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssetsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Ambil UUID untuk status 'active' dan 'inactive' dari tabel master_assets_status
        $activeStatusId = \DB::table('master_assets_status')
            ->where('name', 'active')
            ->value('id'); // Ambil UUID untuk status 'active'

        $inactiveStatusId = \DB::table('master_assets_status')
            ->where('name', 'inactive')
            ->value('id'); // Ambil UUID untuk status 'inactive'

        // Hitung total aset
        $totalAssets = Asset::count();

        // Hitung total aset dengan status 'active'
        $totalAssetsActive = Asset::where('status_id', $activeStatusId)->count();

        // Hitung total aset dengan status 'inactive'
        $totalAssetsInactive = Asset::where('status_id', $inactiveStatusId)->count();

        return [
            Stat::make('Total Aset', $totalAssets)
                ->color('primary')
                ->description('Total Semua Aset'),
            Stat::make('Aset Aktif', $totalAssetsActive)
                ->color('primary')
                ->description('Total Aset Aktif'),
            Stat::make('Aset Inaktif', $totalAssetsInactive)
                ->color('primary')
                ->description('Total Aset Inaktif'),
        ];
    }
}
