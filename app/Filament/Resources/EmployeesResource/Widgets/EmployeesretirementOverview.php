<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Employees;
use Carbon\Carbon;

class EmployeesRetirementOverview extends BaseWidget
{

    protected static ?string $heading = 'Daftar Pensiun Tahunan';

    public function table(Table $table): Table
    {
        $currentYear = Carbon::now()->year;

        return $table
            ->query(
                Employees::query()->whereNotNull('retirement')->whereYear('retirement', $currentYear)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('age')
                    ->label('Umur'),
                Tables\Columns\TextColumn::make('retirement')
                    ->label('Tanggal Pensiun')
                    ->date('d/m/Y'),


            ]);
    }
}
