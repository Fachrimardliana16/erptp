<?php

namespace App\Filament\Resources\InventoryPackageResource\Pages;

use App\Filament\Resources\InventoryPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryPackage extends EditRecord
{
    protected static string $resource = InventoryPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
