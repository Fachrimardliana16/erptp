<?php

namespace App\Filament\Resources\MasterBillingServiceTypeResource\Pages;

use App\Filament\Resources\MasterBillingServiceTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingServiceTypes extends ListRecords
{
    protected static string $resource = MasterBillingServiceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
