<?php

namespace App\Filament\Resources\EmployeeBenefitResource\Pages;

use App\Filament\Resources\EmployeeBenefitResource;
use App\Models\EmployeeBenefit;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEmployeeBenefit extends CreateRecord
{
    protected static string $resource = EmployeeBenefitResource::class;

    // Override fungsi create default
    protected function handleRecordCreation(array $data): Model
    {
        return EmployeeBenefit::create([
            'employee_id' => $data['employee_id'],
            'benefits' => $data['benefits'],
            'users_id' => $data['users_id'],
        ]);
    }
}
