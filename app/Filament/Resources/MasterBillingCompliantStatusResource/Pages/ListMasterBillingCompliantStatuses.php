<?php

namespace App\Filament\Resources\MasterBillingCompliantStatusResource\Pages;

use App\Filament\Resources\MasterBillingCompliantStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingCompliantStatuses extends ListRecords
{
    protected static string $resource = MasterBillingCompliantStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
