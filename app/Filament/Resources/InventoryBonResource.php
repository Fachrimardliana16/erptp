<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryBonResource\Pages;
use App\Filament\Resources\InventoryBonResource\RelationManagers;
use App\Models\InventoryBon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryBonResource extends Resource
{
    protected static ?string $model = InventoryBon::class;
    protected static ?string $navigationGroup = 'Inventory (beta)';
    protected static ?string $navigationLabel = 'Bon Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('note_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('bon_date')
                    ->required(),
                Forms\Components\TextInput::make('requested_by')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('used_for')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('edit_date')
                    ->required(),
                Forms\Components\TextInput::make('edited_by')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bpp_id')
                    ->required()
                    ->maxLength(36),
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
                Tables\Columns\TextColumn::make('note_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bon_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('requested_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('used_for')
                    ->searchable(),
                Tables\Columns\TextColumn::make('edit_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('edited_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bpp_id')
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
            'index' => Pages\ListInventoryBons::route('/'),
            'create' => Pages\CreateInventoryBon::route('/create'),
            'edit' => Pages\EditInventoryBon::route('/{record}/edit'),
        ];
    }
}
