<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeReterimentResource\Pages;
use App\Filament\Resources\EmployeeReterimentResource\RelationManagers;
use App\Models\EmployeeReteriment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeReterimentResource extends Resource
{
    protected static ?string $model = EmployeeReteriment::class;
    protected static ?string $navigationLabel = 'Registrasi Purna Tugas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('registration_number')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('employee_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('users_id')
                    ->required()
                    ->maxLength(36),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registration_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('users_id')
                    ->searchable(),
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
            'index' => Pages\ListEmployeeReteriments::route('/'),
            'create' => Pages\CreateEmployeeReteriment::route('/create'),
            'edit' => Pages\EditEmployeeReteriment::route('/{record}/edit'),
        ];
    }
}
