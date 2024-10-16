<?php

namespace App\Filament\Resources\InventoryReturnDetailResource\Pages;

use App\Filament\Resources\InventoryReturnDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryReturnDetail extends EditRecord
{
    protected static string $resource = InventoryReturnDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
