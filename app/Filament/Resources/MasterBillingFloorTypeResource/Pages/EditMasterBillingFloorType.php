<?php

namespace App\Filament\Resources\MasterBillingFloorTypeResource\Pages;

use App\Filament\Resources\MasterBillingFloorTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingFloorType extends EditRecord
{
    protected static string $resource = MasterBillingFloorTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
