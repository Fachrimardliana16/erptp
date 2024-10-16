<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryReceivedResource\Pages;
use App\Filament\Resources\InventoryReceivedResource\RelationManagers;
use App\Models\InventoryReceived;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryReceivedResource extends Resource
{
    protected static ?string $model = InventoryReceived::class;
    protected static ?string $navigationGroup = 'Inventory (beta)';
    protected static ?string $navigationLabel = 'Recived';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('allocation_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('dpb_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Textarea::make('desc')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('isdeleted')
                    ->required(),
                Forms\Components\Toggle::make('isopeningbalance')
                    ->required(),
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
                Tables\Columns\TextColumn::make('allocation_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dpb_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('isdeleted')
                    ->boolean(),
                Tables\Columns\IconColumn::make('isopeningbalance')
                    ->boolean(),
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
            'index' => Pages\ListInventoryReceiveds::route('/'),
            'create' => Pages\CreateInventoryReceived::route('/create'),
            'edit' => Pages\EditInventoryReceived::route('/{record}/edit'),
        ];
    }
}
