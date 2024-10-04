<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeJobApplicationArchivesResource\Pages;
use App\Filament\Resources\EmployeeJobApplicationArchivesResource\RelationManagers;
use App\Models\EmployeeJobApplicationArchives;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeJobApplicationArchivesResource extends Resource
{
    protected static ?string $model = EmployeeJobApplicationArchives::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Arsip Berkas Lamaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Input Berkas Lamaran')
                    ->description('Input data berkas lamaran.')
                    ->schema([
                        Forms\Components\TextInput::make('registration_number')
                            ->label('Nomor Registrasi')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('registration_date')
                            ->label('Tanggal Registrasi Masuk')
                            ->required(),
                    ]),
                Section::make('')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Pelamar')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->label('alamat')
                            ->required()
                            ->maxLength(255),
                        Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('place_of_birth')
                                    ->label('Tempat Lahir')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->label('Tanggal Lahir')
                                    ->required()
                                    ->columnSpan(1),
                            ])
                            ->columns(2),
                        Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('E-Mail')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('contact')
                                    ->label('Kontak')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                        Forms\Components\Select::make('religion')
                            ->options([
                                'islam' => 'Islam',
                                'kristen' => 'Kristen',
                                'katholik' => 'Katholik',
                                'hindu' => 'Hindu',
                                'budha' => 'Budha',
                                'lainnya' => 'Lain-lain',
                            ])
                            ->label('Agama'),
                        Group::make()
                            ->schema([
                                Forms\Components\Select::make('education')
                                    ->label('Pendidikan')
                                    ->options([
                                        'sd' => 'SD',
                                        'smp' => 'SMP',
                                        'sma' => 'SMA',
                                        'd1' => 'Diploma I',
                                        'd3' => 'Diploma III',
                                        'd4' => 'Diploma IV',
                                        's1' => 'S1',
                                        's2' => 'S2',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('major')
                                    ->label('Jurusan')
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                    ]),
                Section::make('')
                    ->schema([
                        Forms\Components\FileUpload::make('archive_file')
                            ->directory('Employee_JobApplicationArchive')
                            ->label('Berkas Lamaran')
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
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
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('No. Registrasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registration_date')
                    ->label('Tanggal Registrasi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('place_of_birth')
                    ->label('Tempat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact')
                    ->label('Kontak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('religion')
                    ->label('Agama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('education')
                    ->label('Pendidikan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('major')
                    ->label('Jururan')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('archive_file')
                    ->label('Berkas Lamaran')
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
            'index' => Pages\ListEmployeeJobApplicationArchives::route('/'),
            'create' => Pages\CreateEmployeeJobApplicationArchives::route('/create'),
            'edit' => Pages\EditEmployeeJobApplicationArchives::route('/{record}/edit'),
        ];
    }
}
