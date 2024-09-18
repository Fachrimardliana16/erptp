<?php

namespace App\Filament\Resources\MasterBillingVehicleTypeResource\Pages;

use App\Filament\Resources\MasterBillingVehicleTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingVehicleTypes extends ListRecords
{
    protected static string $resource = MasterBillingVehicleTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
