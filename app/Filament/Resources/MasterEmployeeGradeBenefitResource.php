<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterEmployeeGradeBenefitResource\Pages;
use App\Filament\Resources\MasterEmployeeGradeBenefitResource\RelationManagers;
use App\Models\MasterEmployeeGrade;
use App\Models\MasterEmployeeGradeBenefit;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\MasterEmployeeBenefit;
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
                        Forms\Components\Select::make('grade_id')
                            ->options(
                                MasterEmployeeGrade::all()
                                    ->sortBy(function ($grade) {
                                        // Mengembalikan array dengan dua kriteria untuk pengurutan
                                        return [
                                            $grade->name, // Urutkan berdasarkan name
                                            (int) $grade->service_grade // Urutkan berdasarkan service_grade sebagai integer
                                        ];
                                    })
                                    ->mapWithKeys(function ($grade) {
                                        return [$grade->id => "{$grade->name}"];
                                    })
                            )
                            ->label('Golongan')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('benefit_id')
                            ->options(function () {
                                return MasterEmployeeBenefit::all()->pluck('name', 'id');
                            })
                            ->label('Tunjangan')
                            ->searchable()
                            ->preload()
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
                Tables\Columns\TextColumn::make('gradeBenefits.name')
                    ->label('Golongan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('benefit.name')
                    ->label('Tunjangan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
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
