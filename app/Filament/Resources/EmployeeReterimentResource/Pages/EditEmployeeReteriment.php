<?php

namespace App\Filament\Resources\EmployeeReterimentResource\Pages;

use App\Filament\Resources\EmployeeReterimentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeReteriment extends EditRecord
{
    protected static string $resource = EmployeeReterimentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
