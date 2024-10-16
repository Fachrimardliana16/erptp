<?php

namespace App\Filament\Resources\MasterInventoryAllocationResource\Pages;

use App\Filament\Resources\MasterInventoryAllocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterInventoryAllocation extends EditRecord
{
    protected static string $resource = MasterInventoryAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
