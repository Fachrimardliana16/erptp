<?php

namespace App\Filament\Resources\MasterBillingIdTypeResource\Pages;

use App\Filament\Resources\MasterBillingIdTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingIdType extends EditRecord
{
    protected static string $resource = MasterBillingIdTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
