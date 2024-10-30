<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeBusinessTravelLettersResource\Pages;
use App\Filament\Resources\EmployeeBusinessTravelLettersResource\RelationManagers;
use App\Models\EmployeeBusinessTravelLetters;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeBusinessTravelLettersResource extends Resource
{
    protected static ?string $model = EmployeeBusinessTravelLetters::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Surat Perjalanan Dinas';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Input Surat Perjalanan Dinas')
                    ->description('Input data perjalanan dinas.')
                    ->schema([
                        Forms\Components\TextInput::make('registration_number')
                            ->label('Nomor Surat')
                            ->required()
                            ->maxLength(255),
                        Group::make()
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->required(),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Tanggal Selesai')
                            ])
                            ->columns(2),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('businessTravelEmployee', 'name')
                            ->label('Nama Pegawai')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('destination')
                            ->label('Tujuan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Detail Tugas')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('Nomor Surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('businessTravelEmployee.name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('destination')
                    ->label('Tujuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Tugas')
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
            'index' => Pages\ListEmployeeBusinessTravelLetters::route('/'),
            'create' => Pages\CreateEmployeeBusinessTravelLetters::route('/create'),
            'edit' => Pages\EditEmployeeBusinessTravelLetters::route('/{record}/edit'),
        ];
    }
}
