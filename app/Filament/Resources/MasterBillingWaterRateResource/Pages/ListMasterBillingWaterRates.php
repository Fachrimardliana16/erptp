<?php

namespace App\Filament\Resources\MasterBillingWaterRateResource\Pages;

use App\Filament\Resources\MasterBillingWaterRateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingWaterRates extends ListRecords
{
    protected static string $resource = MasterBillingWaterRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
