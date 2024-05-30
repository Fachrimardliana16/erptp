<?php

namespace App\Filament\Resources\EmployeePromotionResource\Pages;

use App\Filament\Resources\EmployeePromotionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeePromotion extends EditRecord
{
    protected static string $resource = EmployeePromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['users_id'] = auth()->id();

        return $data;
    }
}
