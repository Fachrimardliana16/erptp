<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeTrainingResource\Pages;
use App\Filament\Resources\EmployeeTrainingResource\RelationManagers;
use App\Models\EmployeeTraining;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeTrainingResource extends Resource
{
    protected static ?string $model = EmployeeTraining::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Pelatihan/Diklat';
    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Diklat Pegawai')
                    ->description('Form input data diklat pegawai')
                    ->schema([
                        Forms\Components\DatePicker::make('training_date')
                            ->label('Tanggal Pelatihan/Diklat')
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employeeTraining', 'name')
                            ->label('Pegawai')
                            ->required()
                            ->required(),
                        Forms\Components\TextInput::make('training_title')
                            ->label('Judul Pelatihan/Diklat')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('training_location')
                            ->label('Lokasi')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('organizer')
                            ->label('Penyelenggara')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('photo_training')
                            ->directory('Foto Pelatihan')
                            ->label('Bukti Foto Pelaksanaan')
                            ->required()
                            ->helperText('Unggah foto pelaksanaan pelatihan.')
                            ->rules(['required', 'mimes:jpeg,jpg,png', 'max:5120']), // Allowing image formats and max 5MB
                        Forms\Components\FileUpload::make('docs_training')
                            ->directory('Dokumen Sertifikat')
                            ->label('Lampiran Sertifikat')
                            ->required()
                            ->helperText('Unggah dokumen sertifikat pelatihan.')
                            ->rules(['required', 'mimes:pdf', 'max:5120']), // Only PDF and max 5MB
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('training_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('training_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('training_location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('organizer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salary_increase')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo_training')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('docs_training')
                    ->searchable(),
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
            'index' => Pages\ListEmployeeTrainings::route('/'),
            'create' => Pages\CreateEmployeeTraining::route('/create'),
            'edit' => Pages\EditEmployeeTraining::route('/{record}/edit'),
        ];
    }
}
