<?php

namespace App\Filament\Resources\InventoryDpbResource\Pages;

use App\Filament\Resources\InventoryDpbResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryDpb extends EditRecord
{
    protected static string $resource = InventoryDpbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
