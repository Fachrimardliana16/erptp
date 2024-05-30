<?php

namespace App\Filament\Resources\MasterEmployeeBenefitResource\Pages;

use App\Filament\Resources\MasterEmployeeBenefitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeBenefit extends EditRecord
{
    protected static string $resource = MasterEmployeeBenefitResource::class;

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
