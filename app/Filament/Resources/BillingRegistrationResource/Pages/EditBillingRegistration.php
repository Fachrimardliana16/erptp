<?php

namespace App\Filament\Resources\BillingRegistrationResource\Pages;

use App\Filament\Resources\BillingRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBillingRegistration extends EditRecord
{
    protected static string $resource = BillingRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
