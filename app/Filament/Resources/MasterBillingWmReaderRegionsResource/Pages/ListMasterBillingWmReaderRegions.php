<?php

namespace App\Filament\Resources\MasterBillingWmReaderRegionsResource\Pages;

use App\Filament\Resources\MasterBillingWmReaderRegionsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingWmReaderRegions extends ListRecords
{
    protected static string $resource = MasterBillingWmReaderRegionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
