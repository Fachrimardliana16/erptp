<?php

namespace App\Filament\Resources\InventoryReceivedResource\Pages;

use App\Filament\Resources\InventoryReceivedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryReceived extends EditRecord
{
    protected static string $resource = InventoryReceivedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
