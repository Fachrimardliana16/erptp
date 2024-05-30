<?php

namespace App\Filament\Resources\MasterAssetsMoneyVoucherTypeResource\Pages;

use App\Filament\Resources\MasterAssetsMoneyVoucherTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterAssetsMoneyVoucherTypes extends ListRecords
{
    protected static string $resource = MasterAssetsMoneyVoucherTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
