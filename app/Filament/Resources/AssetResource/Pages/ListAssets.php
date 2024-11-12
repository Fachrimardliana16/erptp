<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Filament\Resources\AssetResource\Widgets\AssetOverview;
use App\Filament\Resources\AssetResource\Widgets\AssetsOverview;
use App\Filament\Resources\AssetResource\Widgets\AssetStatusChartOverview;
use App\Filament\Resources\AssetResource\Widgets\AssetStatusOverview;
use App\Filament\Widgets\TotalWidget;
use App\Models\Asset;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ListAssets extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        // Ambil semua data kondisi dari tabel master_assets_condition
        $conditions = DB::table('master_assets_condition')->pluck('id', 'name');

        // Inisialisasi array tabs dengan entri default 'Semua'
        $tabs = [
            'Semua' => Tab::make()->badge(function () {
                return Asset::count(); // Total semua aset
            }),
        ];

        // Tambahkan entri dinamis untuk setiap kondisi dengan badge
        foreach ($conditions as $name => $id) {
            $tabs[$name] = Tab::make()
                ->query(function ($query) use ($id) {
                    return $query->where('condition_id', $id);
                })
                ->badge(function () use ($id) {
                    return Asset::where('condition_id', $id)->count(); // Jumlah aset untuk kondisi tertentu
                });
        }

        return $tabs;
    }



    protected function getHeaderWidgets(): array
    {
        return [
            AssetStatusOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AssetStatusChartOverview::class,
            AssetOverview::class,
        ];
    }
}
