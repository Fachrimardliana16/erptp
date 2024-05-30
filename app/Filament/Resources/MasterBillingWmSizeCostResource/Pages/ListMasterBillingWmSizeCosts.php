<?php

namespace App\Filament\Resources\MasterBillingWmSizeCostResource\Pages;

use App\Filament\Resources\MasterBillingWmSizeCostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingWmSizeCosts extends ListRecords
{
    protected static string $resource = MasterBillingWmSizeCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
