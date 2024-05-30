<?php

namespace App\Filament\Resources\MasterBillingRegistrationTypeResource\Pages;

use App\Filament\Resources\MasterBillingRegistrationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingRegistrationTypes extends ListRecords
{
    protected static string $resource = MasterBillingRegistrationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
