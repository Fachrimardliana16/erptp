<?php

namespace App\Filament\Resources\MoneyVoucherRequestsResource\Pages;

use App\Filament\Resources\MoneyVoucherRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMoneyVoucherRequests extends ListRecords
{
    protected static string $resource = MoneyVoucherRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
