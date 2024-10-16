<?php

namespace App\Filament\Resources\InventoryOpnameResource\Pages;

use App\Filament\Resources\InventoryOpnameResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryOpnames extends ListRecords
{
    protected static string $resource = InventoryOpnameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
