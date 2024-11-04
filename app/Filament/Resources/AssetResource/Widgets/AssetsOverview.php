<?php

namespace App\Filament\Resources\AssetResource\Widgets;

use App\Models\Asset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AssetsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Ambil UUID untuk status 'active' dan 'inactive' dari tabel master_assets_status
        $activeStatusId = DB::table('master_assets_status')
            ->where('name', 'active')
            ->value('id'); // Ambil UUID untuk status 'active'

        $inactiveStatusId = DB::table('master_assets_status')
            ->where('name', 'inactive')
            ->value('id'); // Ambil UUID untuk status 'inactive'

        $categoryAssetElec = DB::table('master_assets_category')
            ->where('name', 'elektronik')
            ->value('id'); // Ambil UUID untuk kategori 'elektronik'

        $categoryAssetFurnitur = DB::table('master_assets_category')
            ->where('name', 'furnitur')
            ->value('id'); // Ambil UUID untuk kategori 'furnitur'

        $categoryAssetKendaraan = DB::table('master_assets_category')
            ->where('name', 'Kendaraan')
            ->value('id'); // Ambil UUID untuk kategori 'kendaraan'

        // Hitung total aset
        $totalAssets = Asset::count();

        // Hitung total aset dengan status 'active'
        $totalAssetsActive = Asset::where('status_id', $activeStatusId)->count();

        // Hitung total aset dengan status 'inactive'
        $totalAssetsInactive = Asset::where('status_id', $inactiveStatusId)->count();
        $totalCategoryElec = Asset::where('category_id', $categoryAssetElec)->count();
        $totalCategoryFurnitur = Asset::where('category_id', $categoryAssetFurnitur)->count();
        $totalCategoryKendaraan = Asset::where('category_id', $categoryAssetKendaraan)->count();

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
            Stat::make('Aset Elektronik', $totalCategoryElec)
                ->color('primary')
                ->description('Total Aset Elektronik'),
            Stat::make('Aset Furnitur', $totalCategoryFurnitur)
                ->color('primary')
                ->description('Total Aset Furnitur'),
            Stat::make('Aset Kendaraan', $totalCategoryKendaraan)
                ->color('primary')
                ->description('Total Aset Kendaraan'),
        ];
    }
}
