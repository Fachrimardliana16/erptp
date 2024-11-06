<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Employees;
use Carbon\Carbon;

class EmployeesPromotionOverview extends BaseWidget
{

    protected static ?string $heading = 'Daftar Kenaikan Golongan';
    public function table(Table $table): Table
    {
        $currentYear = Carbon::now()->year;

        return $table
            ->query(
                Employees::query()
                    ->whereYear('grade_date_end', $currentYear)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('grade_date_end')
                    ->label('Grade End Date')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('employeeGrade', 'name')
                    ->label('Employee Grade')
                    ->sortable(),
            ]);
    }
}
