<?php

namespace App\Filament\Resources\MasterBillingRoofTypeResource\Pages;

use App\Filament\Resources\MasterBillingRoofTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingRoofTypes extends ListRecords
{
    protected static string $resource = MasterBillingRoofTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
