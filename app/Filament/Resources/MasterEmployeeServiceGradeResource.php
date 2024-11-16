<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterEmployeeServiceGradeResource\Pages;
use App\Filament\Resources\MasterEmployeeServiceGradeResource\RelationManagers;
use App\Models\MasterEmployeeGrade;
use App\Models\MasterEmployeeServiceGrade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterEmployeeServiceGradeResource extends Resource
{
    protected static ?string $model = MasterEmployeeServiceGrade::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master Employee';
    protected static ?string $navigationLabel = 'Masa Kerja Golongan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('employee_grade_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('service_grade')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Textarea::make('desc')
                    ->columnSpanFull(),
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
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('employeeGrade.name')
                    ->label('Golongan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_grade')
                    ->label('MKG')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_id')
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
                SelectFilter::make('employee_grade_id') // Nama filter yang akan ditampilkan
                    ->options(
                        MasterEmployeeGrade::all()
                            ->sortBy('name') // Mengurutkan berdasarkan nama
                            ->pluck('name', 'id') // Mengambil nama dan id
                            ->toArray() // Mengonversi koleksi menjadi array
                    )
                    ->placeholder('Pilih Golongan') // Placeholder untuk dropdown
                    ->multiple() // Jika ingin mengizinkan pemilihan ganda
                    ->label('Filter berdasarkan Golongan'),
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
            'index' => Pages\ListMasterEmployeeServiceGrades::route('/'),
            'create' => Pages\CreateMasterEmployeeServiceGrade::route('/create'),
            'edit' => Pages\EditMasterEmployeeServiceGrade::route('/{record}/edit'),
        ];
    }
}
