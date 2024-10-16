<?php

namespace App\Filament\Resources\InventoryReceivedDetailResource\Pages;

use App\Filament\Resources\InventoryReceivedDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryReceivedDetail extends EditRecord
{
    protected static string $resource = InventoryReceivedDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
