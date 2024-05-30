<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetMonitoringResource\Pages;
use App\Filament\Resources\AssetMonitoringResource\RelationManagers;
use App\Models\Asset;
use App\Models\AssetMonitoring;
use App\Models\AssetMutation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetMonitoringResource extends Resource
{
    protected static ?string $model = AssetMonitoring::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kerumah Tanggaan';
    protected static ?string $navigationLabel = 'Monitoring Aset';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Monitoring Aset')
                    ->description('Input data monitoring aset')
                    ->schema([
                        Forms\Components\DatePicker::make('monitoring_date')
                            ->label('Tanggal Monitoring')
                            ->required(),
                        Forms\Components\Select::make('assets_id')
                            ->options(Asset::query()->pluck('assets_number', 'id'))
                            ->afterStateUpdated(function ($set, $state) {
                                $aset = Asset::find($state);
                                if ($aset) {
                                    $set('assets_number', $aset->assets_number);
                                    $set('name', $aset->name);
                                    $set('old_condition_id', $aset->condition_id);
                                }
                            })
                            ->label('Nomor Aset')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Hidden::make('assets_number')
                            ->default(function ($get) {
                                $assets_id = $get('assets_id');
                                $asset = AssetMutation::find($assets_id);
                                return $asset ? $asset->assets_number : null;
                            }),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Aset')
                            ->required()
                            ->readOnly(),
                        Forms\Components\Select::make('old_condition_id')
                            ->relationship('MonitoringoldCondition', 'name')
                            ->label('Kondisi Lama')
                            ->required()
                            ->disabled(),
                        Forms\Components\Hidden::make('old_condition_id')
                            ->default(function ($get) {
                                return $get('old_condition_id');
                            }),
                        Forms\Components\Select::make('new_condition_id')
                            ->relationship('MonitoringNewCondition', 'name')
                            ->label('Kondisi Baru')
                            ->searchable()
                            ->preload()
                            ->required(),
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
                Tables\Columns\TextColumn::make('monitoring_date')
                    ->label('Tanggal Monitoring')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assetMonitoring.name')
                    ->label('Nama Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employeeAssetMonitoring.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monitoringLocation.name')
                    ->label('Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monitoringSubLocation.name')
                    ->label('Sub Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MonitoringoldCondition.name')
                    ->label('Kondisi Lama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('new_condition_id.name')
                    ->label('Kondisi Baru')
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
            'index' => Pages\ListAssetMonitorings::route('/'),
            'create' => Pages\CreateAssetMonitoring::route('/create'),
            'edit' => Pages\EditAssetMonitoring::route('/{record}/edit'),
        ];
    }
}
