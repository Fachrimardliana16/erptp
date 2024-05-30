<?php

namespace App\Filament\Resources\FuelVoucherReturnsResource\Pages;

use App\Filament\Resources\FuelVoucherReturnsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFuelVoucherReturns extends EditRecord
{
    protected static string $resource = FuelVoucherReturnsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
