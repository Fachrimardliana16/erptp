<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterBillingFloorTypeResource\Pages;
use App\Filament\Resources\MasterBillingFloorTypeResource\RelationManagers;
use App\Models\MasterBillingFloorType;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterBillingFloorTypeResource extends Resource
{
    protected static ?string $model = MasterBillingFloorType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Billing';
    protected static ?string $navigationLabel = 'Tipe Lantai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Tipe Lantai')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('score')
                            ->label('Nilai')
                            ->required()
                            ->numeric(),
                        Forms\Components\Textarea::make('desc')
                            ->label('Golongan')
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
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('score')
                    ->label('Nilai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('desc')
                    ->label('Golongan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('users_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListMasterBillingFloorTypes::route('/'),
            'create' => Pages\CreateMasterBillingFloorType::route('/create'),
            'edit' => Pages\EditMasterBillingFloorType::route('/{record}/edit'),
        ];
    }
}
