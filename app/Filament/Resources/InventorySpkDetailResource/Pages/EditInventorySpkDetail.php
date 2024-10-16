<?php

namespace App\Filament\Resources\InventorySpkDetailResource\Pages;

use App\Filament\Resources\InventorySpkDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventorySpkDetail extends EditRecord
{
    protected static string $resource = InventorySpkDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
