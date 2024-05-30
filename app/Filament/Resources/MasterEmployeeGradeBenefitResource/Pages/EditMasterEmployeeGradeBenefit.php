<?php

namespace App\Filament\Resources\MasterEmployeeGradeBenefitResource\Pages;

use App\Filament\Resources\MasterEmployeeGradeBenefitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeGradeBenefit extends EditRecord
{
    protected static string $resource = MasterEmployeeGradeBenefitResource::class;

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
