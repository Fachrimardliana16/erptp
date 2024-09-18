<?php

namespace App\Filament\Resources\AssetRequestsResource\Pages;

use App\Filament\Resources\AssetRequestsResource;
use App\Filament\Resources\AssetRequestsResource\Widgets\AssetRequestsOverview;
use App\Filament\Resources\AssetResource\Widgets\AssetsRequestOverview;
use App\Models\AssetRequests;
use Filament\Actions;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Resources\Components\Tab as ComponentsTab;
use Filament\Resources\Pages\ListRecords;

class ListAssetRequests extends ListRecords
{
    protected static string $resource = AssetRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AssetRequestsOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'Semua' => ComponentsTab::make()->badge(function () {
                return AssetRequests::count(); // Total semua permintaan aset
            }),
            'Belum Selesai' => ComponentsTab::make()->badge(function () {
                return AssetRequests::where('status_request', false)->count(); // Jumlah permintaan aset yang belum selesai
            }),
            'Selesai' => ComponentsTab::make()->badge(function () {
                return AssetRequests::where('status_request', true)->count(); // Jumlah permintaan aset yang sudah selesai
            }),
        ];
    }
}
