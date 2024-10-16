<?php

namespace App\Filament\Resources\InventoryReturnDetailResource\Pages;

use App\Filament\Resources\InventoryReturnDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryReturnDetails extends ListRecords
{
    protected static string $resource = InventoryReturnDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
