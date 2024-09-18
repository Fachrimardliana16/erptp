<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeePromotionResource\Pages;
use App\Filament\Resources\EmployeePromotionResource\RelationManagers;
use App\Models\EmployeePromotion;
use App\Models\Employees;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeePromotionResource extends Resource
{
    protected static ?string $model = EmployeePromotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Kenaikan Golongan Pegawai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Kenaikan Golongan Pegawai')
                    ->description('Form input kenaikan golongan pegawai')
                    ->schema([
                        Forms\Components\TextInput::make('decision_letter_number')
                            ->label('Nomor Surat Keputusan')
                            ->required(),
                        Forms\Components\DatePicker::make('promotion_date')
                            ->label('Tanggal Kenaikan Golongan')
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->options(Employees::query()->pluck('name', 'id'))
                            ->afterStateUpdated(function ($set, $state) {
                                $employees = Employees::find($state);
                                $set('name', $employees->name);
                                $set('old_grade_id', $employees->employee_grade_id);
                                $set('old_basic_salary_id', $employees->employee_basic_salary_id);
                            })
                            ->label('Nama Pegawai')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Hidden::make('employee_id')
                            ->label('Nama Pegawai')
                            ->required(),
                        Forms\Components\Select::make('old_grade_id')
                            ->relationship('oldGrade', 'name')
                            ->label('Golongan Lama')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('old_basic_salary_id')
                            ->relationship('oldBasicSalary', 'amount')
                            ->label('Gaji Pokok Lama')
                            ->prefix('Rp. ')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('old_basic_salary_id')
                            ->label('Gaji Pokok Lama')
                            ->required(),
                        Forms\Components\Select::make('new_grade_id')
                            ->relationship('newGrade', 'name')
                            ->label('Golongan Baru')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('new_basic_salary_id')
                            ->relationship('newBasicSalary', 'amount')
                            ->label('Gaji Pokok Baru')
                            ->prefix('Rp. ')
                            ->searchable()
                            ->preload()
                            ->required(),
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
                Tables\Columns\TextColumn::make('decision_letter_number')
                    ->label('Nomor SK')
                    ->sortable(),
                Tables\Columns\TextColumn::make('promotion_date')
                    ->label('Tanggal Mutasi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employeePromotion.name')
                    ->label('Nama Pegawai')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('oldGrade.name')
                    ->label('Golongan Lama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('oldBasicSalary.amount')
                    ->label('Gaji Pokok Lama')
                    ->sortable()
                    ->money('Rp. ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('newGrade.name')
                    ->label('Golongan Baru')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('newBasicSalary.amount')
                    ->label('Gaji Pokok Baru')
                    ->money('Rp. ')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
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
            'index' => Pages\ListEmployeePromotions::route('/'),
            'create' => Pages\CreateEmployeePromotion::route('/create'),
            'edit' => Pages\EditEmployeePromotion::route('/{record}/edit'),
        ];
    }
}
