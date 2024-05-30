<?php

namespace App\Filament\Resources\MasterAssetsFuelTypeResource\Pages;

use App\Filament\Resources\MasterAssetsFuelTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterAssetsFuelTypes extends ListRecords
{
    protected static string $resource = MasterAssetsFuelTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
