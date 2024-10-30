<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterEmployeeGradeSalaryCutsResource\Pages;
use App\Filament\Resources\MasterEmployeeGradeSalaryCutsResource\RelationManagers;
use App\Models\MasterEmployeeGradeSalaryCuts;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterEmployeeGradeSalaryCutsResource extends Resource
{
    protected static ?string $model = MasterEmployeeGradeSalaryCuts::class;

    protected static ?string $navigationIcon = 'heroicon-o-minus-circle';
    protected static ?string $navigationGroup = 'Master Employee';
    protected static ?string $navigationLabel = 'Golongan Potongan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Potongan Golongan')
                    ->description('Input Potongan Golongan pada form di bawah ini.')
                    ->schema([
                        Forms\Components\Select::make('salary_cuts_id')
                            ->relationship('SalaryCuts', 'name')
                            ->label('Nama Potongan')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('grade_id')
                            ->relationship('GradeCuts', 'name')
                            ->label('Golongan')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric()
                            ->prefix('Rp. '),
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
                Tables\Columns\TextColumn::make('SalaryCuts.name')
                    ->label('Potongan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('GradeCuts.name')
                    ->label('Golongan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
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
            'index' => Pages\ListMasterEmployeeGradeSalaryCuts::route('/'),
            //'create' => Pages\CreateMasterEmployeeGradeSalaryCuts::route('/create'),
            //'edit' => Pages\EditMasterEmployeeGradeSalaryCuts::route('/{record}/edit'),
        ];
    }
}
