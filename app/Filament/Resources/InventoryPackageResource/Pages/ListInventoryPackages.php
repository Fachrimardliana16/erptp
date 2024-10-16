<?php

namespace App\Filament\Resources\InventoryPackageResource\Pages;

use App\Filament\Resources\InventoryPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryPackages extends ListRecords
{
    protected static string $resource = InventoryPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
