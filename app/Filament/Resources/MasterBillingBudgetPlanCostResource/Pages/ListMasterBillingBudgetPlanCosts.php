<?php

namespace App\Filament\Resources\MasterBillingBudgetPlanCostResource\Pages;

use App\Filament\Resources\MasterBillingBudgetPlanCostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingBudgetPlanCosts extends ListRecords
{
    protected static string $resource = MasterBillingBudgetPlanCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
