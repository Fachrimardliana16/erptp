<?php

namespace App\Filament\Resources\MasterBillingWallTypeResource\Pages;

use App\Filament\Resources\MasterBillingWallTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingWallTypes extends ListRecords
{
    protected static string $resource = MasterBillingWallTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
