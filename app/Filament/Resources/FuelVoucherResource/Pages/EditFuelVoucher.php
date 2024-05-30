<?php

namespace App\Filament\Resources\FuelVoucherResource\Pages;

use App\Filament\Resources\FuelVoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFuelVoucher extends EditRecord
{
    protected static string $resource = FuelVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
