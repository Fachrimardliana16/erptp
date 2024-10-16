<?php

namespace App\Filament\Resources\InventoryBonDetailResource\Pages;

use App\Filament\Resources\InventoryBonDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryBonDetail extends EditRecord
{
    protected static string $resource = InventoryBonDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
