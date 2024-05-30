<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterBillingWmSizeCostResource\Pages;
use App\Filament\Resources\MasterBillingWmSizeCostResource\RelationManagers;
use App\Models\MasterBillingWmSizeCost;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterBillingWmSizeCostResource extends Resource
{
    protected static ?string $model = MasterBillingWmSizeCost::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master Billing';
    protected static ?string $navigationLabel = 'Dana Meter';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Dana Meter')
                    ->description('Input perhitungan dana meter')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('wm_size')
                            ->label('Diameter SR')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cost')
                            ->label('Biaya')
                            ->required()
                            ->numeric('IDR')
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('admin_cost')
                            ->label('Biaya admin')
                            ->required()
                            ->numeric('IDR')
                            ->prefix('Rp'),
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
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wm_size')
                    ->label('Diameter SR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Biaya')
                    ->prefix('Rp. ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('admin_cost')
                    ->label('Biaya Admin')
                    ->prefix('Rp. ')
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
            'index' => Pages\ListMasterBillingWmSizeCosts::route('/'),
            //'create' => Pages\CreateMasterBillingWmSizeCost::route('/create'),
            //'edit' => Pages\EditMasterBillingWmSizeCost::route('/{record}/edit'),
        ];
    }
}
