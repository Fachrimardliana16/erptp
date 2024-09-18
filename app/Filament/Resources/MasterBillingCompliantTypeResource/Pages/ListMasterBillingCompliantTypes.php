<?php

namespace App\Filament\Resources\MasterBillingCompliantTypeResource\Pages;

use App\Filament\Resources\MasterBillingCompliantTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingCompliantTypes extends ListRecords
{
    protected static string $resource = MasterBillingCompliantTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
