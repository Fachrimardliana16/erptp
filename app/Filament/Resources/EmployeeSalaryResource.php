<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeSalaryResource\Pages;
use App\Filament\Resources\EmployeeSalaryResource\RelationManagers;
use App\Models\EmployeeSalary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeSalaryResource extends Resource
{
    protected static ?string $model = EmployeeSalary::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Gaji Pegawai';

    // Metode untuk menghitung total bruto
    protected static function calculateTotalBruto($get)
    {
        return ($get('basic_salary') ?? 0) + ($get('benefits_1') ?? 0) + ($get('benefits_2') ?? 0) +
            ($get('benefits_3') ?? 0) + ($get('benefits_4') ?? 0) + ($get('benefits_5') ?? 0) +
            ($get('benefits_6') ?? 0) + ($get('benefits_7') ?? 0) + ($get('benefits_8') ?? 0) +
            ($get('Rounding') ?? 0) + ($get('Incentive') ?? 0) + ($get('Backpay') ?? 0);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Master Gaji Pegawai')
                    ->description('Form input master gaji pegawai')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'name')
                            ->label('Nama Pegawai')
                            ->required(),
                        Fieldset::make('Gaji dan Tunjangan')
                            ->schema([
                                TextInput::make('basic_salary')
                                    ->prefix('Rp. ')
                                    ->label('Gaji Pokok')
                                    ->numeric()
                                    ->reactive()
                                    ->debounce(300)
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('benefits_1')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan Keluarga')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('benefits_2')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan Beras')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('benefits_3')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan Jabatan')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('benefits_4')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan Kesehatan')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('benefits_5')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan Air')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('benefits_6')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan DPLK')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('benefits_7')
                                    ->prefix('Rp. ')
                                    ->label('Lain-lain')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('benefits_12')
                                    ->prefix('Rp. ')
                                    ->label('Lain-lain')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('Rounding')
                                    ->prefix('Rp. ')
                                    ->label('Pembulatan')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('Incentive')
                                    ->prefix('Rp. ')
                                    ->label('Insentif')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('Backpay')
                                    ->prefix('Rp. ')
                                    ->label('Rapel')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                                TextInput::make('amount')
                                    ->prefix('Rp. ')
                                    ->label('Total Bruto')
                                    ->disabled()
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                            ]),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_1')
                    ->label('Tunjangan Keluarga')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_2')
                    ->label('Tunjangan beras')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_3')
                    ->label('Tunjangan Jabatan')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_4')
                    ->label('Tunjangan Kesehatan')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_5')
                    ->label('Tunjangan Air')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_6')
                    ->label('Tunjangan DPLK')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_7')
                    ->label('Lain-lain 1')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_12')
                    ->label('Lain-lain 2')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Rounding')
                    ->label('Pembulatan')
                    ->numeric()
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('Incentive')
                    ->label('Insentif')
                    ->numeric()
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('Backpay')
                    ->label('Rapel')
                    ->numeric()
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Total Bruto')
                    ->numeric()
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('users_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeSalaries::route('/'),
            'create' => Pages\CreateEmployeeSalary::route('/create'),
            'edit' => Pages\EditEmployeeSalary::route('/{record}/edit'),
        ];
    }
}
