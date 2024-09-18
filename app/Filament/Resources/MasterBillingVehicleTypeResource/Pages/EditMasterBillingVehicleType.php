<?php

namespace App\Filament\Resources\MasterBillingVehicleTypeResource\Pages;

use App\Filament\Resources\MasterBillingVehicleTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingVehicleType extends EditRecord
{
    protected static string $resource = MasterBillingVehicleTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
