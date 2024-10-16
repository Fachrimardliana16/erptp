<?php

namespace App\Filament\Resources\InventoryDpbDetailResource\Pages;

use App\Filament\Resources\InventoryDpbDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryDpbDetail extends EditRecord
{
    protected static string $resource = InventoryDpbDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
