<?php

namespace App\Filament\Resources\InventorySpkDetailResource\Pages;

use App\Filament\Resources\InventorySpkDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventorySpkDetails extends ListRecords
{
    protected static string $resource = InventorySpkDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
