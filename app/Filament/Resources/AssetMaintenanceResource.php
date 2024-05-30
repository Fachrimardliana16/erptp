<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetMaintenanceResource\Pages;
use App\Filament\Resources\AssetMaintenanceResource\RelationManagers;
use App\Models\AssetMaintenance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetMaintenanceResource extends Resource
{
    protected static ?string $model = AssetMaintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kerumah Tanggaan';
    protected static ?string $navigationLabel = 'Pemeliharaan Aset';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Pemeliharaan Aset')
                    ->description('Input data pemeliharaan aset')
                    ->schema([
                        Forms\Components\DatePicker::make('maintenance_date')
                            ->label('Tanggal Pemeliharaan')
                            ->required(),
                        Forms\Components\TextInput::make('location_service')
                            ->label('Lokasi Service')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('assets_id')
                            ->relationship('AssetMaintenance', 'name')
                            ->label('Nama Aset')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('service_type')
                            ->options([
                                'Perbaikian Ringan' => 'Perbaikian Ringan',
                                'Perbaikian Sedang' => 'Perbaikian Sedang',
                                'Perbaikian Berat' => 'Perbaikan Berat',
                            ])
                            ->label('Tipe Service')
                            ->required(),
                        Forms\Components\TextInput::make('service_cost')
                            ->label('Total Biaya Service')
                            ->prefix('Rp. ')
                            ->required()
                            ->numeric(),
                        Forms\Components\FileUpload::make('invoice_file')
                            ->label('Struck'),
                        Forms\Components\Textarea::make('desc')
                            ->label('Catatan Service')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('maintenance_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_service')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assets_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_cost')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_file')
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
            'index' => Pages\ListAssetMaintenances::route('/'),
            'create' => Pages\CreateAssetMaintenance::route('/create'),
            'edit' => Pages\EditAssetMaintenance::route('/{record}/edit'),
        ];
    }
}
