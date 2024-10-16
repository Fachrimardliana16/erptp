<?php

namespace App\Filament\Resources\MasterInventoryAllocationResource\Pages;

use App\Filament\Resources\MasterInventoryAllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterInventoryAllocations extends ListRecords
{
    protected static string $resource = MasterInventoryAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
