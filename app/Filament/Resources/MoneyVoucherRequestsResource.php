<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MoneyVoucherRequestsResource\Pages;
use App\Filament\Resources\MoneyVoucherRequestsResource\RelationManagers;
use App\Models\MoneyVoucherRequests;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MoneyVoucherRequestsResource extends Resource
{
    protected static ?string $model = MoneyVoucherRequests::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Permintaan Kas Kecil';
    protected static ?string $navigationLabel = 'Permintaan Bon Uang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('FORM PERMINTAAN KAS')
                    ->description('Form permintaan KAS Kecil dan KAS Besar')
                    ->schema([
                        Forms\Components\TextInput::make('voucher_number')
                            ->label('Nomor Voucher')
                            ->placeholder('Nomor Urut / Hari / Bulan / Tahun')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Permintaan')
                            ->required(),
                        Forms\Components\Select::make('voucher_status_type_id')
                            ->relationship('voucherStatusType', 'name')
                            ->label('Status Permintaan')
                            ->required(),
                        Forms\Components\Select::make('money_voucher_id')
                            ->relationship('moneyVoucherType', 'name')
                            ->label('Tipe Permintaan Kas')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah')
                            ->prefix('Rp. ')
                            ->required()
                            ->numeric(),
                        Forms\Components\Textarea::make('usage_purpose')
                            ->label('Keperluan')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employeeMoneyVoucher', 'name')
                            ->label('Pengguna')
                            ->required(),
                        Forms\Components\FileUpload::make('docs')
                            ->directory('Money Request')
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
                    ->label('Tanggal Permintaan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('voucherStatusType.name')
                    ->label('Status Permintaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('moneyVoucherType.name')
                    ->label('Tiper Permintaan KAS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->prefix('Rp. ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employeeMoneyVoucher.name')
                    ->label('Pengguna')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('docs')
                    ->label('Bukti Permintaan')
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
            'index' => Pages\ListMoneyVoucherRequests::route('/'),
            'create' => Pages\CreateMoneyVoucherRequests::route('/create'),
            'edit' => Pages\EditMoneyVoucherRequests::route('/{record}/edit'),
        ];
    }
}
