<?php

namespace App\Filament\Resources\FuelVoucherResource\Pages;

use App\Filament\Resources\FuelVoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFuelVouchers extends ListRecords
{
    protected static string $resource = FuelVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
