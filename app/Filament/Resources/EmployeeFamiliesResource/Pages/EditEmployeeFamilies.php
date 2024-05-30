<?php

namespace App\Filament\Resources\EmployeeFamiliesResource\Pages;

use App\Filament\Resources\EmployeeFamiliesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeFamilies extends EditRecord
{
    protected static string $resource = EmployeeFamiliesResource::class;

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
