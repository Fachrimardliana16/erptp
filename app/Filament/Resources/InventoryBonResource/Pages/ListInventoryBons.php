<?php

namespace App\Filament\Resources\InventoryBonResource\Pages;

use App\Filament\Resources\InventoryBonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryBons extends ListRecords
{
    protected static string $resource = InventoryBonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
