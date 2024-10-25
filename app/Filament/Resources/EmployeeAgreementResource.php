<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeAgreementResource\Pages;
use App\Filament\Resources\EmployeeAgreementResource\RelationManagers;
use App\Models\EmployeeAgreement;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\EmployeeJobAplicationArchives;
use App\Models\EmployeeJobApplicationArchives;
use App\Models\Employees;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;

class EmployeeAgreementResource extends Resource
{
    protected static ?string $model = EmployeeAgreement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Perjanjian Kontrak';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Perjanjian Kontrak Pegawai')
                    ->description('Input data perjanjian kontrak pegawai.')
                    ->schema([
                        TextInput::make('agreement_number')
                            ->label('Nomor Perjanjian Kontrak')
                            ->maxLength(255)
                            ->validationAttribute('Nomor Perjanjian Kontrak')
                            ->required(),
                        Select::make('job_application_archives_id')
                            ->options(function () {
                                return EmployeeJobApplicationArchives::query()
                                    ->get()
                                    ->mapWithKeys(function ($archive) {
                                        return [$archive->id => $archive->registration_number . ' | ' . $archive->name];
                                    })
                                    ->toArray();
                            })
                            ->afterStateUpdated(function (callable $set, $state) {
                                $archive = EmployeeJobApplicationArchives::find($state);
                                if ($archive) {
                                    $set('name', $archive->name);
                                    $set('hidden_name', $archive->name); // Update hidden name field
                                }
                            })
                            ->reactive()
                            ->label('Nomor Registrasi Lamaran')
                            ->required()
                            ->validationAttribute('Nomor Registrasi Lamaran'),

                        TextInput::make('name')
                            ->label('Nama Pegawai')
                            ->disabled()
                            ->required()
                            ->dehydrated(false)
                            ->validationAttribute('Nama Pegawai'),
                        Hidden::make('name')
                            ->label('Nama Pegawai')
                            ->required()
                            ->validationAttribute('Nama Pegawai'),
                        Select::make('agreement_id')
                            ->relationship('agreement', 'name')
                            ->label('Status Kontrak')
                            ->required()
                            ->validationAttribute('Status Kontrak'),
                        Select::make('employee_position_id')
                            ->relationship('agreementPosition', 'name')
                            ->label('Jabatan')
                            ->required()
                            ->validationAttribute('Jabatan'),
                        Select::make('status_employemnts_id')
                            ->relationship('agreementStatus', 'name')
                            ->label('Status Pegawai')
                            ->required()
                            ->validationAttribute('Status Pegawai'),
                        DatePicker::make('agreement_date_start')
                            ->label('Tanggal Mulai Perjanjian')
                            ->required()
                            ->validationAttribute('Tanggal Mulai Perjanjian'),
                        DatePicker::make('agreement_date_end')
                            ->label('Tanggal Akhir Perjanjian')
                            ->required()
                            ->validationAttribute('Tanggal Akhir Perjanjian'),
                        FileUpload::make('docs')
                            ->directory('Perjanjian Kontrak')
                            ->label('Lampiran Dokumen')
                            ->validationAttribute('Lampiran Dokumen'),
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
                Tables\Columns\TextColumn::make('agreement_number')
                    ->label('Nomor Surat Perjanjian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreement.name')
                    ->label('Perjanjian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreementPosition.name')
                    ->label('Jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreementStatus.name')
                    ->label('Status Kepegawaian')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agreement_date_start')
                    ->label('Tanggal Mulai Perjanjian Kontrak')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agreement_date_end')
                    ->label('Tanggal Akhir Perjanjian Kontrak')
                    ->date()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('docs')
                    ->label('Lampiran Dokumen')
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
            'index' => Pages\ListEmployeeAgreements::route('/'),
            'create' => Pages\CreateEmployeeAgreement::route('/create'),
            'edit' => Pages\EditEmployeeAgreement::route('/{record}/edit'),
        ];
    }
}
