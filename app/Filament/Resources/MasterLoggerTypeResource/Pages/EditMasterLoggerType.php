<?php

namespace App\Filament\Resources\MasterLoggerTypeResource\Pages;

use App\Filament\Resources\MasterLoggerTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterLoggerType extends EditRecord
{
    protected static string $resource = MasterLoggerTypeResource::class;

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
