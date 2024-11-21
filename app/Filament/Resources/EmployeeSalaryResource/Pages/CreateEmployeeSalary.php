<?php
// Pages/CreateEmployeeSalary.php
namespace App\Filament\Resources\EmployeeSalaryResource\Pages;

use App\Filament\Resources\EmployeeSalaryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeSalary extends CreateRecord
{
    protected static string $resource = EmployeeSalaryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
