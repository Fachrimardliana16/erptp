<?php

namespace App\Filament\Resources\MasterInventoryGroupResource\Pages;

use App\Filament\Resources\MasterInventoryGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterInventoryGroups extends ListRecords
{
    protected static string $resource = MasterInventoryGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
