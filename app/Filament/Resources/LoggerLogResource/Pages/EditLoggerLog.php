<?php

namespace App\Filament\Resources\LoggerLogResource\Pages;

use App\Filament\Resources\LoggerLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoggerLog extends EditRecord
{
    protected static string $resource = LoggerLogResource::class;

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
