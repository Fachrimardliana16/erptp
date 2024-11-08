<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeAttendanceRecordsResource\Pages;
use App\Filament\Resources\EmployeeAttendanceRecordsResource\RelationManagers;
use App\Models\EmployeeAttendanceRecords;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeAttendanceRecordsResource extends Resource
{
    protected static ?string $model = EmployeeAttendanceRecords::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Log Presensi (beta)';
    protected static ?int $navigationSort = 12;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('pin')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('employee_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('attendance_time')
                    ->required(),
                Forms\Components\TextInput::make('state')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('verification')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('work_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reserved')
                    ->maxLength(255),
                Forms\Components\TextInput::make('device')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('picture')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pin')
                    ->label('PIN'),
                Tables\Columns\TextColumn::make('employee_name')
                    ->label('Employee Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('attendance_time')
                    ->label('Attendance Time')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->label('State'),
                Tables\Columns\TextColumn::make('verification')
                    ->label('Verification'),
                Tables\Columns\TextColumn::make('work_code')
                    ->label('Work Code'),
                Tables\Columns\TextColumn::make('reserved')
                    ->label('Reserved'),
                Tables\Columns\TextColumn::make('device')
                    ->label('Device')
                    ->searchable(),
                Tables\Columns\TextColumn::make('picture')
                    ->label('Picture'),
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
            'index' => Pages\ListEmployeeAttendanceRecords::route('/'),
            'create' => Pages\CreateEmployeeAttendanceRecords::route('/create'),
            'edit' => Pages\EditEmployeeAttendanceRecords::route('/{record}/edit'),
        ];
    }
}
