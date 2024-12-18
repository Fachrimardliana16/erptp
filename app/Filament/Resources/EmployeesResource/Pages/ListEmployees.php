<?php

namespace App\Filament\Resources\EmployeesResource\Pages;

use App\Filament\Resources\EmployeesResource;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesAgeOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesAgreementChartOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesAgreementOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesBirthdayOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesChartOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesEducationOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesGenderChartOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesGradeOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesPeriodicSalaryIncreaseOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesPromotionOverview;
use App\Filament\Resources\EmployeesResource\Widgets\EmployeesretirementOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EmployeesAgreementOverview::class,
            EmployeesOverview::class,
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [
            //EmployeesChartOverview::class,
            EmployeesGenderChartOverview::class,
            EmployeesEducationOverview::class,
            EmployeesGradeOverview::class,
            EmployeesAgeOverview::class,
            EmployeesPeriodicSalaryIncreaseOverview::class,
            EmployeesPromotionOverview::class,
            EmployeesretirementOverview::class,
            EmployeesBirthdayOverview::class,

        ];
    }
}
