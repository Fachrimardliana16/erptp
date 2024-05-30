<?php

namespace App\Filament\Resources\MasterEmployeeGradeSalaryCutsResource\Pages;

use App\Filament\Resources\MasterEmployeeGradeSalaryCutsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeGradeSalaryCuts extends EditRecord
{
    protected static string $resource = MasterEmployeeGradeSalaryCutsResource::class;

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
