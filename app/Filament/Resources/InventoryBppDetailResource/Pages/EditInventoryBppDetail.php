<?php

namespace App\Filament\Resources\InventoryBppDetailResource\Pages;

use App\Filament\Resources\InventoryBppDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryBppDetail extends EditRecord
{
    protected static string $resource = InventoryBppDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
