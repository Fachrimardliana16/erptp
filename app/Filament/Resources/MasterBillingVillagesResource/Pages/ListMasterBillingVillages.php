<?php

namespace App\Filament\Resources\MasterBillingVillagesResource\Pages;

use App\Filament\Resources\MasterBillingVillagesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingVillages extends ListRecords
{
    protected static string $resource = MasterBillingVillagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
