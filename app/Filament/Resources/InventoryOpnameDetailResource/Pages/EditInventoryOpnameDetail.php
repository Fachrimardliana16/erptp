<?php

namespace App\Filament\Resources\InventoryOpnameDetailResource\Pages;

use App\Filament\Resources\InventoryOpnameDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryOpnameDetail extends EditRecord
{
    protected static string $resource = InventoryOpnameDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
