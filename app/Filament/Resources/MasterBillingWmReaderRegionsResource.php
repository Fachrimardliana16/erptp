<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterBillingWmReaderRegionsResource\Pages;
use App\Filament\Resources\MasterBillingWmReaderRegionsResource\RelationManagers;
use App\Models\MasterBillingWmReaderRegions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterBillingWmReaderRegionsResource extends Resource
{
    protected static ?string $model = MasterBillingWmReaderRegions::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Billing';
    protected static ?string $navigationLabel = 'Water Meter Reader Region';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('region_id')
                    ->relationship('WmReaderRegion', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('billPeriode')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date_read')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('users_id')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('region_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_read')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('billPeriode')
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
            'index' => Pages\ListMasterBillingWmReaderRegions::route('/'),
            'create' => Pages\CreateMasterBillingWmReaderRegions::route('/create'),
            'edit' => Pages\EditMasterBillingWmReaderRegions::route('/{record}/edit'),
        ];
    }
}
