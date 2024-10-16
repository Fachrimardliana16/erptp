<?php

namespace App\Filament\Resources\InventorySpkResource\Pages;

use App\Filament\Resources\InventorySpkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventorySpk extends EditRecord
{
    protected static string $resource = InventorySpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
