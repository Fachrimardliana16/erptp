<?php

namespace App\Filament\Resources\MasterBillingBudgetPlanCostDetailResource\Pages;

use App\Filament\Resources\MasterBillingBudgetPlanCostDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterBillingBudgetPlanCostDetails extends ListRecords
{
    protected static string $resource = MasterBillingBudgetPlanCostDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
