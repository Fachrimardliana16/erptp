<?php

namespace App\Filament\Resources\InventoryBppResource\Pages;

use App\Filament\Resources\InventoryBppResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryBpps extends ListRecords
{
    protected static string $resource = InventoryBppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
