<?php

namespace App\Filament\Resources\MasterAssetsMoneyVoucherTypeResource\Pages;

use App\Filament\Resources\MasterAssetsMoneyVoucherTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterAssetsMoneyVoucherType extends EditRecord
{
    protected static string $resource = MasterAssetsMoneyVoucherTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
