<?php

namespace App\Filament\Resources\MasterEmployeeAgreementResource\Pages;

use App\Filament\Resources\MasterEmployeeAgreementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeeAgreements extends ListRecords
{
    protected static string $resource = MasterEmployeeAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
