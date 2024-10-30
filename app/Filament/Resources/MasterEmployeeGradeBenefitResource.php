<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterEmployeeGradeBenefitResource\Pages;
use App\Filament\Resources\MasterEmployeeGradeBenefitResource\RelationManagers;
use App\Models\MasterEmployeeGradeBenefit;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterEmployeeGradeBenefitResource extends Resource
{
    protected static ?string $model = MasterEmployeeGradeBenefit::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Master Employee';
    protected static ?string $navigationLabel = 'Golongan Tunjangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Golongan Tunjangan')
                    ->description('Input Kecamatan pada form di bawah ini.')
                    ->schema([
                        Forms\Components\Select::make('benefit_id')
                            ->relationship('BenefitGrade', 'name')
                            ->label('Tunjangan')
                            ->required(),
                        Forms\Components\Select::make('grade_id')
                            ->relationship('GradeBenefit', 'name')
                            ->label('Golongan')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric('IDR')
                            ->prefix('Rp'),
                        Forms\Components\Textarea::make('desc')
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
                Tables\Columns\TextColumn::make('BenefitGrade.name')
                    ->label('Tunjangan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('GradeBenefit.name')
                    ->label('Golongan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
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
            'index' => Pages\ListMasterEmployeeGradeBenefits::route('/'),
            //'create' => Pages\CreateMasterEmployeeGradeBenefit::route('/create'),
            //'edit' => Pages\EditMasterEmployeeGradeBenefit::route('/{record}/edit'),
        ];
    }
}
