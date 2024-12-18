<?php

namespace App\Filament\Resources\AssetMaintenanceResource\Pages;

use App\Filament\Resources\AssetMaintenanceResource;
use App\Filament\Resources\AssetMaintenanceResource\Widgets\AssetMaintenanceOverview;
use App\Filament\Resources\AssetMaintenanceResource\Widgets\MaintenanceOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetMaintenances extends ListRecords
{
    protected static string $resource = AssetMaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
