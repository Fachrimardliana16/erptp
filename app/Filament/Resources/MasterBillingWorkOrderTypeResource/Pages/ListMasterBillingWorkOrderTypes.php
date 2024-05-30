<?php

namespace App\Filament\Resources\MasterBillingWorkOrderTypeResource\Pages;

use App\Filament\Resources\MasterBillingWorkOrderTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingWorkOrderTypes extends ListRecords
{
    protected static string $resource = MasterBillingWorkOrderTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
