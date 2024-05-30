<?php

namespace App\Filament\Resources\MasterAssetsVoucherStatusTypeResource\Pages;

use App\Filament\Resources\MasterAssetsVoucherStatusTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasterAssetsVoucherStatusTypes extends ListRecords
{
    protected static string $resource = MasterAssetsVoucherStatusTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
