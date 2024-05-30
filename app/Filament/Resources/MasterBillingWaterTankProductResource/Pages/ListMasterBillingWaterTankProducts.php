<?php

namespace App\Filament\Resources\MasterBillingWaterTankProductResource\Pages;

use App\Filament\Resources\MasterBillingWaterTankProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingWaterTankProducts extends ListRecords
{
    protected static string $resource = MasterBillingWaterTankProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
