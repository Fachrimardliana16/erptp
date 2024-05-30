<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterBillingWaterRateResource\Pages;
use App\Filament\Resources\MasterBillingWaterRateResource\RelationManagers;
use App\Models\MasterBillingWaterRate;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterBillingWaterRateResource extends Resource
{
    protected static ?string $model = MasterBillingWaterRate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master Billing';
    protected static ?string $navigationLabel = 'Harga Air';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Harga Air')
                    ->description('Input kategori aset yang dibutuhkan')
                    ->collapsible()
                    ->schema([
                        Section::make('Detail Harga Air')
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Kode')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('name')
                                    ->label('Golongan Harga Air')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Hidden::make('users_id')
                                    ->default(auth()->id()),
                            ])->columns(2)
                    ]),

                Section::make('Batas m³')
                    ->description('Input batas m³/kubikasi')
                    ->collapsible()
                    ->schema([
                        Section::make('Detail Batas Kubikasi')
                            ->schema([
                                Forms\Components\TextInput::make('limit_1')
                                    ->label('Batas m³ 1')
                                    ->prefix('m³')
                                    ->numeric(),
                                Forms\Components\TextInput::make('limit_2')
                                    ->label('Batas m³ 2')
                                    ->prefix('m³')
                                    ->numeric(),
                                Forms\Components\TextInput::make('limit_3')
                                    ->label('Batas m³ 3')
                                    ->prefix('m³')
                                    ->numeric(),
                            ])->columns(3)
                    ]),

                Section::make('Batas Biaya')
                    ->description('Input batas biaya')
                    ->collapsible()
                    ->schema([
                        Section::make('Detail Batas Kubikasi')
                            ->schema([
                                Forms\Components\TextInput::make('cost_1')
                                    ->label('Biaya 1')
                                    ->prefix('Rp')
                                    ->numeric(),
                                Forms\Components\TextInput::make('cost_2')
                                    ->label('Biaya 2')
                                    ->prefix('Rp')
                                    ->numeric(),
                                Forms\Components\TextInput::make('cost_3')
                                    ->label('Biaya 3')
                                    ->prefix('Rp')
                                    ->numeric(),
                                Forms\Components\TextInput::make('cost_4')
                                    ->label('Biaya 4')
                                    ->prefix('Rp')
                                    ->numeric(),
                            ])->columns(2)
                    ]),

                Section::make('Form Kas')
                    ->schema([
                        Section::make('Detail Kas')
                            ->schema([
                                Forms\Components\TextInput::make('minimum_cost')
                                    ->label('Biaya Minimum')
                                    ->prefix('Rp')
                                    ->numeric(),
                                Forms\Components\TextInput::make('meter_subscription')
                                    ->label('Biaya Abonemen Meter')
                                    ->prefix('Rp')
                                    ->numeric(),
                                Forms\Components\TextInput::make('kas_1')
                                    ->label('Kas 1')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('kas_2')
                                    ->label('Kas 2')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('kas_3')
                                    ->label('Kas 3')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('finnest')
                                    ->label('Finnest')
                                    ->numeric()
                                    ->prefix('Rp'),
                            ])
                    ]),
            ])->columns(1);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('limit_1')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('limit_2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('limit_3')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_1')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_3')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_4')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('minimum_cost')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('meter_subscription')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kas_1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kas_2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kas_3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('finnest')
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
            'index' => Pages\ListMasterBillingWaterRates::route('/'),
            'create' => Pages\CreateMasterBillingWaterRate::route('/create'),
            'edit' => Pages\EditMasterBillingWaterRate::route('/{record}/edit'),
        ];
    }
}
