<?php

namespace App\Filament\Resources\EmployeeMutationsResource\Pages;

use App\Filament\Resources\EmployeeMutationsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeMutations extends EditRecord
{
    protected static string $resource = EmployeeMutationsResource::class;

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
