<?php

namespace App\Filament\Resources\MoneyVoucherReturnsResource\Pages;

use App\Filament\Resources\MoneyVoucherReturnsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMoneyVoucherReturns extends EditRecord
{
    protected static string $resource = MoneyVoucherReturnsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
