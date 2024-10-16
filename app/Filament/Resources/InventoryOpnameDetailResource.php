<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryOpnameDetailResource\Pages;
use App\Filament\Resources\InventoryOpnameDetailResource\RelationManagers;
use App\Models\InventoryOpnameDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryOpnameDetailResource extends Resource
{
    protected static ?string $model = InventoryOpnameDetail::class;
    protected static ?string $navigationGroup = 'Inventory (beta)';
    protected static ?string $navigationLabel = 'Opname Detail';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('item_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('received_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('opname_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('item_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('received_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('opname_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('users_id')
                    ->searchable(),
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
            'index' => Pages\ListInventoryOpnameDetails::route('/'),
            'create' => Pages\CreateInventoryOpnameDetail::route('/create'),
            'edit' => Pages\EditInventoryOpnameDetail::route('/{record}/edit'),
        ];
    }
}
