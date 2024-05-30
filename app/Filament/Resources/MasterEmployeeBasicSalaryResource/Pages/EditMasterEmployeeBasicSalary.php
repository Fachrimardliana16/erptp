<?php

namespace App\Filament\Resources\MasterEmployeeBasicSalaryResource\Pages;

use App\Filament\Resources\MasterEmployeeBasicSalaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeBasicSalary extends EditRecord
{
    protected static string $resource = MasterEmployeeBasicSalaryResource::class;

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
