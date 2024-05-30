<?php

namespace App\Filament\Resources\MasterBillingCustomerStatusResource\Pages;

use App\Filament\Resources\MasterBillingCustomerStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingCustomerStatuses extends ListRecords
{
    protected static string $resource = MasterBillingCustomerStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
