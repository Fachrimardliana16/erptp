<?php

namespace App\Filament\Resources\InventoryReceivedDetailResource\Pages;

use App\Filament\Resources\InventoryReceivedDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryReceivedDetails extends ListRecords
{
    protected static string $resource = InventoryReceivedDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
