<?php

namespace App\Filament\Resources\MasterInventoryItemTypeResource\Pages;

use App\Filament\Resources\MasterInventoryItemTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterInventoryItemTypes extends ListRecords
{
    protected static string $resource = MasterInventoryItemTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
