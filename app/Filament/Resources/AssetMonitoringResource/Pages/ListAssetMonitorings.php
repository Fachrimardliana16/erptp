<?php

namespace App\Filament\Resources\AssetMonitoringResource\Pages;

use App\Filament\Resources\AssetMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetMonitorings extends ListRecords
{
    protected static string $resource = AssetMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
