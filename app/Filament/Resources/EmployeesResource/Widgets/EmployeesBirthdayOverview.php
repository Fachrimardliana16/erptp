<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Employees;
use Carbon\Carbon;

class EmployeesBirthdayOverview extends BaseWidget
{

    protected static ?string $heading = 'Daftar Ulang Tahun Bulan Ini';
    public function table(Table $table): Table
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return $table
            ->query(
                Employees::query()
                    ->whereMonth('date_birth', $currentMonth)
                    ->whereYear('date_birth', '<=', $currentYear)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('age')
                    ->label('Umur')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_birth')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y'),
            ]);
    }
}
