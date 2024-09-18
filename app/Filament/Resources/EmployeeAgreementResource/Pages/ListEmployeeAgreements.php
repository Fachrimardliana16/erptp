<?php

namespace App\Filament\Resources\EmployeeAgreementResource\Pages;

use App\Filament\Resources\EmployeeAgreementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeAgreements extends ListRecords
{
    protected static string $resource = EmployeeAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
