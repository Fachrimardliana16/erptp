<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterBillingBudgetPlanCostDetailResource\Pages;
use App\Filament\Resources\MasterBillingBudgetPlanCostDetailResource\RelationManagers;
use App\Models\MasterBillingBudgetPlanCostDetail;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterBillingBudgetPlanCostDetailResource extends Resource
{
    protected static ?string $model = MasterBillingBudgetPlanCostDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master Billing';
    protected static ?string $navigationLabel = 'Detail RAB';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('From Detail RAB')
                    ->schema([
                        Forms\Components\Select::make('budget_plan_cost_id')
                            ->relationship('budgetPlanCost', 'name')
                            ->label('Tipe RAB')
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cost')
                            ->label('Biaya')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('ppnp')
                            ->label('PPNP')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('ppn_cost')
                            ->label('Biaya PPNP')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('detail_total')
                            ->label('Total Biaya')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
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
                Tables\Columns\TextColumn::make('budget_plan_cost_id')
                    ->label('Tipe RAB')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Biaya')
                    ->money('Rp')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ppnp')
                    ->label('PPNP')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ppn_cost')
                    ->label('Biaya PPNP')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('detail_total')
                    ->label('Jumalah Biaya')
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
                Tables\Columns\TextColumn::make('users_id')
                    ->searchable()
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
            'index' => Pages\ListMasterBillingBudgetPlanCostDetails::route('/'),
            'create' => Pages\CreateMasterBillingBudgetPlanCostDetail::route('/create'),
            'edit' => Pages\EditMasterBillingBudgetPlanCostDetail::route('/{record}/edit'),
        ];
    }
}
