<?php

namespace App\Filament\Resources\EmployeeJobApplicationArchivesResource\Pages;

use App\Filament\Resources\EmployeeJobApplicationArchivesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeJobApplicationArchives extends EditRecord
{
    protected static string $resource = EmployeeJobApplicationArchivesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
