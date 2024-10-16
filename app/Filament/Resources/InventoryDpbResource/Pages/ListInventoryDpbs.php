<?php

namespace App\Filament\Resources\InventoryDpbResource\Pages;

use App\Filament\Resources\InventoryDpbResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryDpbs extends ListRecords
{
    protected static string $resource = InventoryDpbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
