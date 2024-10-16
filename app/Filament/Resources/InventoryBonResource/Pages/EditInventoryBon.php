<?php

namespace App\Filament\Resources\InventoryBonResource\Pages;

use App\Filament\Resources\InventoryBonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryBon extends EditRecord
{
    protected static string $resource = InventoryBonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
