<?php

namespace App\Filament\Resources\MasterEmployeeSalaryCutsResource\Pages;

use App\Filament\Resources\MasterEmployeeSalaryCutsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterEmployeeSalaryCuts extends EditRecord
{
    protected static string $resource = MasterEmployeeSalaryCutsResource::class;

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
