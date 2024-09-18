<?php

namespace App\Filament\Resources\MasterBillingCompliantTypeResource\Pages;

use App\Filament\Resources\MasterBillingCompliantTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingCompliantType extends EditRecord
{
    protected static string $resource = MasterBillingCompliantTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
