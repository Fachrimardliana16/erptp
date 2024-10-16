<?php

namespace App\Filament\Resources\InventoryBppResource\Pages;

use App\Filament\Resources\InventoryBppResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryBpp extends EditRecord
{
    protected static string $resource = InventoryBppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
