<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterBillingPaymentOfficeResource\Pages;
use App\Filament\Resources\MasterBillingPaymentOfficeResource\RelationManagers;
use App\Models\MasterBillingPaymentOffice;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterBillingPaymentOfficeResource extends Resource
{
    protected static ?string $model = MasterBillingPaymentOffice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master Billing';
    protected static ?string $navigationLabel = 'Lokasi Pembayaran Rekening';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Daftar Lokasi Pembayaran Rekening')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->label('Alamat')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('isactive')
                            ->label('Status Aktivasi'),
                        Forms\Components\Toggle::make('isdepositmode')
                            ->label('Status Mode Deposit'),
                        Forms\Components\DatePicker::make('registered_date')
                            ->label('Tanggal Registrasi')
                            ->required(),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                        Forms\Components\TextInput::make('payment_cost')
                            ->label('Jumlah Pembayaran')
                            ->required()
                            ->numeric(),
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
                Tables\Columns\TextColumn::make('code')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('')
                    ->searchable(),
                Tables\Columns\IconColumn::make('isactive')
                    ->label('')
                    ->boolean(),
                Tables\Columns\IconColumn::make('isdepositmode')
                    ->label('')
                    ->boolean(),
                Tables\Columns\TextColumn::make('registered_date')
                    ->label('')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('users_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_cost')
                    ->label('')
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
            'index' => Pages\ListMasterBillingPaymentOffices::route('/'),
            'create' => Pages\CreateMasterBillingPaymentOffice::route('/create'),
            'edit' => Pages\EditMasterBillingPaymentOffice::route('/{record}/edit'),
        ];
    }
}
