<?php

namespace App\Filament\Resources\MasterBillingSubdistrictsResource\Pages;

use App\Filament\Resources\MasterBillingSubdistrictsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingSubdistricts extends ListRecords
{
    protected static string $resource = MasterBillingSubdistrictsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
