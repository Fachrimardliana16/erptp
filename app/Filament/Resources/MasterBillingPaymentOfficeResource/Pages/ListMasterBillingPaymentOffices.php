<?php

namespace App\Filament\Resources\MasterBillingPaymentOfficeResource\Pages;

use App\Filament\Resources\MasterBillingPaymentOfficeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingPaymentOffices extends ListRecords
{
    protected static string $resource = MasterBillingPaymentOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
