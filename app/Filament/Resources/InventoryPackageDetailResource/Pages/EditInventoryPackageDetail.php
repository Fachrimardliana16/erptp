<?php

namespace App\Filament\Resources\InventoryPackageDetailResource\Pages;

use App\Filament\Resources\InventoryPackageDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryPackageDetail extends EditRecord
{
    protected static string $resource = InventoryPackageDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
