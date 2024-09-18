<?php

namespace App\Filament\Resources\BillingRegistrationResource\Pages;

use App\Filament\Resources\BillingRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBillingRegistrations extends ListRecords
{
    protected static string $resource = BillingRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
