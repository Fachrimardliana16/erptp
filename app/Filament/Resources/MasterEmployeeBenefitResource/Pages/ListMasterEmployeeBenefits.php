<?php

namespace App\Filament\Resources\MasterEmployeeBenefitResource\Pages;

use App\Filament\Resources\MasterEmployeeBenefitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterEmployeeBenefits extends ListRecords
{
    protected static string $resource = MasterEmployeeBenefitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
