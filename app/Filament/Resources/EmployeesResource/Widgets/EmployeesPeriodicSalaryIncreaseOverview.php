<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Employees;
use Carbon\Carbon;

class EmployeesPeriodicSalaryIncreaseOverview extends BaseWidget
{

    protected static ?string $heading = 'Daftar Kenaikan Berkala';
    public function table(Table $table): Table
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return $table
            ->query(
                Employees::query()
                    ->whereMonth('periodic_salary_date_end', $currentMonth)
                    ->whereYear('periodic_salary_date_end', $currentYear)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('periodic_salary_date_end')
                    ->label('Periodic Salary End Date')
                    ->date('d/m/Y'),
            ]);
    }
}
