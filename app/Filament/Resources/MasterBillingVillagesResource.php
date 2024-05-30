<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterBillingVillagesResource\Pages;
use App\Filament\Resources\MasterBillingVillagesResource\RelationManagers;
use App\Models\MasterBillingVillages;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterBillingVillagesResource extends Resource
{
    protected static ?string $model = MasterBillingVillages::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Kelurahan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Kelurahan Aset')
                    ->description('Input Kelurahan pada form di bawah ini.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Keluarahan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('subdistricts_id')
                            ->relationship('VillageSubdistricts', 'name')
                            ->label('Kecamatan')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ])->columns(1);
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
                    ->label('Keluarahan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('VillageSubdistricts.name')
                    ->label('Kecamatan')
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
            'index' => Pages\ListMasterBillingVillages::route('/'),
            'create' => Pages\CreateMasterBillingVillages::route('/create'),
            'edit' => Pages\EditMasterBillingVillages::route('/{record}/edit'),
        ];
    }
}
