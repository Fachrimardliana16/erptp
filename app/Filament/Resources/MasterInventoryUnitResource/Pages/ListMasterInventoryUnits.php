<?php

namespace App\Filament\Resources\MasterInventoryUnitResource\Pages;

use App\Filament\Resources\MasterInventoryUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterInventoryUnits extends ListRecords
{
    protected static string $resource = MasterInventoryUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
