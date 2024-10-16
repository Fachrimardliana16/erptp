<?php

namespace App\Filament\Resources\MasterInventoryGroupResource\Pages;

use App\Filament\Resources\MasterInventoryGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterInventoryGroup extends EditRecord
{
    protected static string $resource = MasterInventoryGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
