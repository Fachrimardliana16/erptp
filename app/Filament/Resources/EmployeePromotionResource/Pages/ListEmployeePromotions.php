<?php

namespace App\Filament\Resources\EmployeePromotionResource\Pages;

use App\Filament\Resources\EmployeePromotionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeePromotions extends ListRecords
{
    protected static string $resource = EmployeePromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
