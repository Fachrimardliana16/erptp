<?php

namespace App\Filament\Resources\InventoryOpeningBalanceResource\Pages;

use App\Filament\Resources\InventoryOpeningBalanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryOpeningBalance extends EditRecord
{
    protected static string $resource = InventoryOpeningBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
