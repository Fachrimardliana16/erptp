<?php

namespace App\Filament\Resources\InventoryDpbDetailResource\Pages;

use App\Filament\Resources\InventoryDpbDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryDpbDetails extends ListRecords
{
    protected static string $resource = InventoryDpbDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
