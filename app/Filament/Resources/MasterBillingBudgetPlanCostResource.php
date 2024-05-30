<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterBillingBudgetPlanCostResource\Pages;
use App\Filament\Resources\MasterBillingBudgetPlanCostResource\RelationManagers;
use App\Models\MasterBillingBudgetPlanCost;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterBillingBudgetPlanCostResource extends Resource
{
    protected static ?string $model = MasterBillingBudgetPlanCost::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master Billing';
    protected static ?string $navigationLabel = 'Rencana Anggaran Biaya';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Rencana Anggaran Biaya (RAB)')
                    ->description('Input dasar penetapan biaya RAB')
                    ->schema([
                        Forms\Components\Select::make('registrationtype_id')
                            ->relationship('RegistrationType', 'name')
                            ->label('Tipe Pendaftaran')
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Sub Total')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('totalppnrp')
                            ->label('Total PPN Rupiah')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('totalcost')
                            ->label('Total Biaya')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\Toggle::make('isactive')
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
                Tables\Columns\IconColumn::make('isactive')
                    ->label('Status Aktivasi')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('RegistrationType.name')
                    ->label('Tipe Pendaftaran')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Sub Total')
                    ->sortable()
                    ->prefix('Rp. ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('totalppnrp')
                    ->label('Total Ppn')
                    ->sortable()
                    ->prefix('Rp. ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('totalcost')
                    ->label('Total')
                    ->prefix('Rp. ')
                    ->sortable()
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
            'index' => Pages\ListMasterBillingBudgetPlanCosts::route('/'),
            //'create' => Pages\CreateMasterBillingBudgetPlanCost::route('/create'),
            //'edit' => Pages\EditMasterBillingBudgetPlanCost::route('/{record}/edit'),
        ];
    }
}
