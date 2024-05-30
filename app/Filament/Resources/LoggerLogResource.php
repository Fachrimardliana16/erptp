<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoggerLogResource\Pages;
use App\Filament\Resources\LoggerLogResource\RelationManagers;
use App\Models\LoggerLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoggerLogResource extends Resource
{
    protected static ?string $model = LoggerLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Logger';
    protected static ?string $navigationLabel = 'Logger Log';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('logger_info_id')
                    ->relationship('loggerInfo', 'name')
                    ->Label('Nama Logger')
                    ->required(),
                Forms\Components\TextInput::make('sensor_value')
                    ->Label('Nilai Sensor')
                    ->required()
                    ->numeric(),
                Forms\Components\DateTimePicker::make('logger_time')
                    ->Label('Waktu Logger')
                    ->required(),
                Forms\Components\DateTimePicker::make('server_time')
                    ->Label('Server Time')
                    ->required(),
                Forms\Components\Hidden::make('users_id')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('loggerInfo.name')
                    ->label('Nama Logger')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sensor_value')
                    ->label('Nilai Sensor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('logger_time')
                    ->label('Waktu Sensor')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('server_time')
                    ->label('Waktu Server')
                    ->dateTime()
                    ->sortable(),
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListLoggerLogs::route('/'),
            //'create' => Pages\CreateLoggerLog::route('/create'),
            //'edit' => Pages\EditLoggerLog::route('/{record}/edit'),
        ];
    }
}
