<?php

namespace App\Filament\Resources\MasterBillingRoofTypeResource\Pages;

use App\Filament\Resources\MasterBillingRoofTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingRoofType extends EditRecord
{
    protected static string $resource = MasterBillingRoofTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
