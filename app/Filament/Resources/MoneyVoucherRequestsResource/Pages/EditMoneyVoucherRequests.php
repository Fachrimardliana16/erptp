<?php

namespace App\Filament\Resources\MoneyVoucherRequestsResource\Pages;

use App\Filament\Resources\MoneyVoucherRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMoneyVoucherRequests extends EditRecord
{
    protected static string $resource = MoneyVoucherRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
