<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryBppResource\Pages;
use App\Filament\Resources\InventoryBppResource\RelationManagers;
use App\Models\InventoryBpp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryBppResource extends Resource
{
    protected static ?string $model = InventoryBpp::class;
    protected static ?string $navigationGroup = 'Inventory (beta)';
    protected static ?string $navigationLabel = 'BPP';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number_bpp')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('request_by')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('allocation_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('nolang')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('desc')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('used_for')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('users_id')
                    ->required()
                    ->maxLength(36),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_bpp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('request_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('allocation_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nolang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('used_for')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_id')
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
            'index' => Pages\ListInventoryBpps::route('/'),
            'create' => Pages\CreateInventoryBpp::route('/create'),
            'edit' => Pages\EditInventoryBpp::route('/{record}/edit'),
        ];
    }
}
