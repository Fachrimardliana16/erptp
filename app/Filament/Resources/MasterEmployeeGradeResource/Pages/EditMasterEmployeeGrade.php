<?php

namespace App\Filament\Resources\MasterEmployeeGradeResource\Pages;

use App\Filament\Resources\MasterEmployeeGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeGrade extends EditRecord
{
    protected static string $resource = MasterEmployeeGradeResource::class;

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
