<?php

namespace App\Filament\Resources\MoneyVoucherReturnsResource\Pages;

use App\Filament\Resources\MoneyVoucherReturnsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMoneyVoucherReturns extends ListRecords
{
    protected static string $resource = MoneyVoucherReturnsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
