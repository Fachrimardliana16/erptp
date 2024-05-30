<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MoneyVoucherReturnsResource\Pages;
use App\Filament\Resources\MoneyVoucherReturnsResource\RelationManagers;
use App\Models\MoneyVoucherRequests;
use App\Models\MoneyVoucherReturns;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MoneyVoucherReturnsResource extends Resource
{
    protected static ?string $model = MoneyVoucherReturns::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kerumah Tanggaan';
    protected static ?string $navigationLabel = 'Pengembalian Bon Uang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('FORM PENYELESAIAN PERMINTAAN KAS')
                    ->description('Isi form penyelesaian permintaan kas untuk menyelesaikan transaki permintaan KAS')
                    ->schema([
                        Forms\Components\DatePicker::make('date_voucher_returns')
                            ->label('Tanggal Penyelesaian')
                            ->required(),
                        Forms\Components\Select::make('money_voucher_request_id')
                            ->options(MoneyVoucherRequests::query()->pluck('voucher_number', 'id')->toArray())
                            ->afterStateUpdated(function ($set, $state) {
                                $fuelvoucher = MoneyVoucherRequests::find($state);
                                $set('voucher_number', $fuelvoucher->voucher_number);
                                $set('date', $fuelvoucher->date);
                                $set('amount', $fuelvoucher->amount);
                                $set('money_voucher_id', $fuelvoucher->money_voucher_id);
                                $set('usage_purpose', $fuelvoucher->usage_purpose);
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
                            ->readonly(),
                        Forms\Components\Hidden::make('date')
                            ->required(),
                        Forms\Components\Select::make('money_voucher_id')
                            ->relationship('moneyVoucherType', 'name')
                            ->label('Tipe Permintaan KAS')
                            ->required(),
                        Forms\Components\Hidden::make('money_voucher_id')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Total')
                            ->required()
                            ->numeric(),
                        Forms\Components\Hidden::make('amount')
                            ->required(),
                        Forms\Components\Textarea::make('usage_purpose')
                            ->label('Keperluan')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('usage_purpose')
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employeeMoneyVoucherReturns', 'name')
                            ->label('Pengguna')
                            ->required(),
                        Forms\Components\Hidden::make('employee_id')
                            ->required(),
                        Forms\Components\TextInput::make('total_amont')
                            ->label('Total Jumlah')
                            ->prefix('Rp. ')
                            ->required()
                            ->numeric(),
                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('docs')
                            ->required(),
                        Forms\Components\FileUpload::make('docs_return')
                            ->directory('Money Voucher Return')
                            ->label('Bukti Penyelesaian'),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('money_voucher_request_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_voucher_returns')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('voucher_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('voucher_status_type_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('money_voucher_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amont')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('docs')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_id')
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
            'index' => Pages\ListMoneyVoucherReturns::route('/'),
            'create' => Pages\CreateMoneyVoucherReturns::route('/create'),
            'edit' => Pages\EditMoneyVoucherReturns::route('/{record}/edit'),
        ];
    }
}
