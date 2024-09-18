<?php

namespace App\Filament\Resources\MasterBillingPaymentOfficeResource\Pages;

use App\Filament\Resources\MasterBillingPaymentOfficeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterBillingPaymentOffice extends EditRecord
{
    protected static string $resource = MasterBillingPaymentOfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
