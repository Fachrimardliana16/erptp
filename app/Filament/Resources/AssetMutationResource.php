<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetMutationResource\Pages;
use App\Filament\Resources\AssetMutationResource\RelationManagers;
use App\Models\Asset;
use App\Models\AssetMutation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetMutationResource extends Resource
{
    protected static ?string $model = AssetMutation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kerumah Tanggaan';
    protected static ?string $navigationLabel = 'Mutasi Aset';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Mutasi Aset')
                    ->description('Form input mutasi aset')
                    ->schema([
                        Forms\Components\TextInput::make('mutations_number')
                            ->label('Nomor Mutasi Aset')
                            ->required(),
                        Forms\Components\DatePicker::make('mutation_date')
                            ->label('Tanggal Mutasi')
                            ->required(),
                        Forms\Components\Select::make('transaction_status_id')
                            ->relationship('AssetsMutationtransactionStatus', 'name')
                            ->label('Status Mutasi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('assets_id')
                            ->options(Asset::query()->pluck('assets_number', 'id'))
                            ->afterStateUpdated(function ($set, $state) {
                                $aset = Asset::find($state);
                                $set('assets_number', $aset->assets_number);
                                $set('name', $aset->name);
                                $set('condition_id', $aset->condition_id);
                            })
                            ->label('Nomor Aset')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Hidden::make('assets_number')
                            ->default(function ($get) {
                                $assets_id = $get('assets_id');
                                $asset = Asset::find($assets_id);
                                return $asset ? $asset->assets_number : null;
                            }),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Aset')
                            ->required()
                            ->readonly(),
                        Forms\Components\Select::make('condition_id')
                            ->relationship('MutationCondition', 'name')
                            ->label('Kondisi')
                            ->required()
                            ->disabled(),
                        Forms\Components\Hidden::make('condition_id')
                            ->default(function ($get) {
                                return $get('condition_id');
                            }),
                        Forms\Components\Select::make('employees_id')
                            ->relationship('AssetsMutationemployee', 'name')
                            ->label('Pemegang')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('location_id')
                            ->relationship('AssetsMutationlocation', 'name')
                            ->label('Lokasi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('sub_location_id')
                            ->relationship('AssetsMutationsubLocation', 'name')
                            ->label('Sub Lokasi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\FileUpload::make('scan_doc')
                            ->directory('Mutation Assets')
                            ->label('Scan Dokumen'),
                        Forms\Components\Textarea::make('desc')
                            ->label('Keterangan')
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
                Tables\Columns\TextColumn::make('mutation_date')
                    ->label('Tanggal Mutasi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('AssetsMutationtransactionStatus.name')
                    ->label('Status Transaksi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetsMutation.assets_number')
                    ->label('Nomor Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetsMutation.name')
                    ->label('Nama Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MutationCondition.name')
                    ->label('Kondisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetsMutationemployee.name')
                    ->label('Pemegang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetsMutationlocation.name')
                    ->label('Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetsMutationsubLocation.name')
                    ->label('Sub Lokasi')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('scan_doc')
                    ->label('Scan Dokumen')
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
            'index' => Pages\ListAssetMutations::route('/'),
            'create' => Pages\CreateAssetMutation::route('/create'),
            'edit' => Pages\EditAssetMutation::route('/{record}/edit'),
        ];
    }
}
