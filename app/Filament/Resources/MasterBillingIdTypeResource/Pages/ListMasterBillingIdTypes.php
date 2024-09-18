<?php

namespace App\Filament\Resources\MasterBillingIdTypeResource\Pages;

use App\Filament\Resources\MasterBillingIdTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingIdTypes extends ListRecords
{
    protected static string $resource = MasterBillingIdTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
