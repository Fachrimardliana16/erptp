<?php

namespace App\Filament\Resources\MasterEmployeeGradeBenefitResource\Pages;

use App\Filament\Resources\MasterEmployeeGradeBenefitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeeGradeBenefits extends ListRecords
{
    protected static string $resource = MasterEmployeeGradeBenefitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
