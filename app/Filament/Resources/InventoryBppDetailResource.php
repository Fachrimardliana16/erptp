<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryBppDetailResource\Pages;
use App\Filament\Resources\InventoryBppDetailResource\RelationManagers;
use App\Models\InventoryBppDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryBppDetailResource extends Resource
{
    protected static ?string $model = InventoryBppDetail::class;
    protected static ?string $navigationGroup = 'Inventory (beta)';
    protected static ?string $navigationLabel = 'BPP Detail';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('bpp_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('item_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('received_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('amount_req')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('amount_out')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('desc')
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
                Tables\Columns\TextColumn::make('bpp_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('received_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_req')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_out')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListInventoryBppDetails::route('/'),
            'create' => Pages\CreateInventoryBppDetail::route('/create'),
            'edit' => Pages\EditInventoryBppDetail::route('/{record}/edit'),
        ];
    }
}
