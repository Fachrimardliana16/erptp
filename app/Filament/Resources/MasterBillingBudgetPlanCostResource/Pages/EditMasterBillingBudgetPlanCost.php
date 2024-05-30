<?php

namespace App\Filament\Resources\MasterBillingBudgetPlanCostResource\Pages;

use App\Filament\Resources\MasterBillingBudgetPlanCostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingBudgetPlanCost extends EditRecord
{
    protected static string $resource = MasterBillingBudgetPlanCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['users_id'] = auth()->id();

        return $data;
    }
}
