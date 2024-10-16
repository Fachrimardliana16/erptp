<?php

namespace App\Filament\Resources\InventoryReceivedResource\Pages;

use App\Filament\Resources\InventoryReceivedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryReceiveds extends ListRecords
{
    protected static string $resource = InventoryReceivedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
