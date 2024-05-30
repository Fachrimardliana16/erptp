<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoggerInfoResource\Pages;
use App\Filament\Resources\LoggerInfoResource\RelationManagers;
use App\Models\LoggerInfo;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoggerInfoResource extends Resource
{
    protected static ?string $model = LoggerInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Logger';
    protected static ?string $navigationLabel = 'Profil Logger';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Golongan Tunjangan')
                    ->description('Input Kecamatan pada form di bawah ini.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('logger_type_id')
                            ->relationship('loggerType', 'name')
                            ->label('Tipe Logger')
                            ->required(),
                        Forms\Components\DateTimePicker::make('build_date')
                            ->label('Tanggal Pembuatan')
                            ->required(),
                        Forms\Components\DateTimePicker::make('activation_date')
                            ->label('Tanggal Aktivasi')
                            ->required(),
                        Forms\Components\TextInput::make('lat')
                            ->label('Lat')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('lon')
                            ->label('lon')
                            ->required()
                            ->numeric(),
                        Forms\Components\DateTimePicker::make('treatment_date')
                            ->label('Tanggal Perawatan')
                            ->required(),
                        Forms\Components\Textarea::make('desc')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])->columns(2)
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('loggerType.name')
                    ->label('Tipe Logger')
                    ->searchable(),
                Tables\Columns\TextColumn::make('build_date')
                    ->label('Tanggal Pembuatan')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('activation_date')
                    ->label('Tanggal Aktivasi')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lat')
                    ->label('Lat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lon')
                    ->label('Lon')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('treatment_date')
                    ->label('Tanggal Perawatan')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLoggerInfos::route('/'),
            //'create' => Pages\CreateLoggerInfo::route('/create'),
            //'edit' => Pages\EditLoggerInfo::route('/{record}/edit'),
        ];
    }
}
