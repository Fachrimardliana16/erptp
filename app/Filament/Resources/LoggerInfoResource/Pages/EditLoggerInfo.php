<?php

namespace App\Filament\Resources\LoggerInfoResource\Pages;

use App\Filament\Resources\LoggerInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoggerInfo extends EditRecord
{
    protected static string $resource = LoggerInfoResource::class;

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
