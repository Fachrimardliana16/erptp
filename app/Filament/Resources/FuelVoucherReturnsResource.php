<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FuelVoucherReturnsResource\Pages;
use App\Filament\Resources\FuelVoucherReturnsResource\RelationManagers;
use App\Models\FuelVoucher;
use App\Models\FuelVoucherReturns;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FuelVoucherReturnsResource extends Resource
{
    protected static ?string $model = FuelVoucherReturns::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Permintaan Kas Kecil';
    protected static ?string $navigationLabel = 'Pengembalian Bahan Bakar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        Forms\Components\DatePicker::make('date_returns')
                            ->label('Tanggal Penyelesaian')
                            ->required(),
                        Forms\Components\Select::make('fuel_voucher_id')
                            ->options(FuelVoucher::query()->pluck('voucher_number', 'id')->toArray())
                            ->afterStateUpdated(function ($set, $state) {
                                $fuelvoucher = FuelVoucher::find($state);
                                $set('voucher_number', $fuelvoucher->voucher_number);
                                $set('date', $fuelvoucher->date);
                                $set('amount', $fuelvoucher->amount);
                                $set('fuel_type_id', $fuelvoucher->fuel_type_id);
                                $set('vehicle_number', $fuelvoucher->vehicle_number);
                                $set('usage_description', $fuelvoucher->usage_description);
                                $set('employee_id', $fuelvoucher->employee_id);
                                $set('docs', $fuelvoucher->docs);
                            })
                            ->label('Nomor Voucher')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Hidden::make('voucher_number')
                            ->label('Nomor Voucher')
                            ->required(),
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Permintaan')
                            ->required()
                            ->readOnly(),
                        Forms\Components\Hidden::make('date')
                            ->label('Tanggal Permintaan')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Total')
                            ->prefix('Rp. ')
                            ->required()
                            ->readOnly()
                            ->numeric(),
                        Forms\Components\Hidden::make('amount')
                            ->label('Total')
                            ->required(),
                        Forms\Components\Select::make('fuel_type_id')
                            ->relationship('fuelType', 'name')
                            ->label('Tipe Bahan Bakar')
                            ->required(),
                        Forms\Components\Hidden::make('fuel_type_id')
                            ->label('Tipe Bahan Bakar')
                            ->required(),
                        Forms\Components\TextInput::make('vehicle_number')
                            ->label('Nomor Kendaraan')
                            ->readOnly()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Hidden::make('vehicle_number')
                            ->label('Nomor Kendaraan')
                            ->required(),
                        Forms\Components\Textarea::make('usage_description')
                            ->label('Keperluan')
                            ->readOnly()
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('usage_description')
                            ->label('Keperluan')
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employeeFuelVoucherReturns', 'name')
                            ->label('Pengguna')
                            ->required(),
                        Forms\Components\Hidden::make('employee_id')
                            ->label('Pengguna')
                            ->required(),
                        // Forms\Components\FileUpload::make('docs')
                        //     ->label('Bukti Permintaan')
                        //     ->required(),
                        Forms\Components\Hidden::make('docs')
                            ->label('Bukti Permintaan')
                            ->required(),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Jumlah')
                            ->prefix('Rp. ')
                            ->required()
                            ->numeric(),
                        Forms\Components\FileUpload::make('docs_return')
                            ->label('Bukti Pengembalian')
                            ->required(),
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
                Tables\Columns\TextColumn::make('date_returns')
                    ->label('Tanggal Pengembalian')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('voucher_number')
                    ->label('Nomor Voucher')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal Permintaan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah Permintaan')
                    ->prefix('Rp. ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fuelType.name')
                    ->label('Jenis Bahan Bakar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_number')
                    ->label('Nomor Kendaraan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employeeFuelVoucherReturns.name')
                    ->label('Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Penggunaan')
                    ->prefix('Rp. ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('docs')
                    ->label('Bukti Permintaan')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('docs_return')
                    ->label('Bukti Pengembalian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_id')
                    ->toggleable(isToggledHiddenByDefault: true)
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
            'index' => Pages\ListFuelVoucherReturns::route('/'),
            'create' => Pages\CreateFuelVoucherReturns::route('/create'),
            'edit' => Pages\EditFuelVoucherReturns::route('/{record}/edit'),
        ];
    }
}
