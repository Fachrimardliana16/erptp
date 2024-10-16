<?php

namespace App\Filament\Resources\InventorySpkResource\Pages;

use App\Filament\Resources\InventorySpkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventorySpks extends ListRecords
{
    protected static string $resource = InventorySpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
