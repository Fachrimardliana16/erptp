<?php

namespace App\Filament\Resources\MasterBillingBudgetPlanCostDetailResource\Pages;

use App\Filament\Resources\MasterBillingBudgetPlanCostDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingBudgetPlanCostDetail extends EditRecord
{
    protected static string $resource = MasterBillingBudgetPlanCostDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
