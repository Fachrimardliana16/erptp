<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FuelVoucherResource\Pages;
use App\Filament\Resources\FuelVoucherResource\RelationManagers;
use App\Models\FuelVoucher;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FuelVoucherResource extends Resource
{
    protected static ?string $model = FuelVoucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kerumah Tanggaan';
    protected static ?string $navigationLabel = 'Permintaan Bahan Bakar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        Forms\Components\TextInput::make('voucher_number')
                            ->label('Nomor Voucher')
                            ->placeholder('Nomot urut/tanggal/bulan/tahun')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Permintaan')
                            ->required(),
                        Forms\Components\Select::make('voucher_status_type_id')
                            ->relationship('voucherStatusType', 'name')
                            ->label('Status Transaksi')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Total')
                            ->prefix('Rp. ')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('fuel_type_id')
                            ->relationship('fuelType', 'name')
                            ->label('Tipe Bahan Bakar')
                            ->required(),
                        Forms\Components\TextInput::make('vehicle_number')
                            ->label('Nomor Kendaraan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('usage_description')
                            ->label('Keperluan')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employeeFuelVoucher', 'name')
                            ->label('Pengguna')
                            ->required(),
                        Forms\Components\FileUpload::make('docs')
                            ->directory('Fuel Voucher')
                            ->label('Bukti Permintaan')
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
                Tables\Columns\TextColumn::make('voucher_number')
                    ->label('Nomor Voucher')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal Pemintaan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('voucherStatusType.name')
                    ->label('Status Voucher')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fuelType.name')
                    ->label('Jenis BBM')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_number')
                    ->label('Nomor Kendaraan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employeeFuelVoucher.name')
                    ->label('Pengguna')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('docs')
                    ->label('Bukti Permintaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_id')
                    ->label('Input By')
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
            'index' => Pages\ListFuelVouchers::route('/'),
            'create' => Pages\CreateFuelVoucher::route('/create'),
            'edit' => Pages\EditFuelVoucher::route('/{record}/edit'),
        ];
    }
}
