<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeesResource\Pages;
use App\Filament\Resources\EmployeesResource\RelationManagers;
use App\Models\Employees;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\fileUpload;

class EmployeesResource extends Resource
{
    protected static ?string $model = Employees::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Data Pegawai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Biodata Pegawai')
                    ->description('Form Biodata Pegawai')
                    ->collapsed(true)
                    ->schema([
                        TextInput::make('nippam')
                            ->label('NIPPAM')
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Nama Pegawai')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('place_birth')
                            ->label('Tempat Lahir')
                            ->maxLength(255),
                        DatePicker::make('date_birth')
                            ->label('Tanggal Lahir')
                            ->live()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state) {
                                    $dateOfBirth = Carbon::createFromFormat('Y-m-d', $state);
                                    $age = $dateOfBirth->age;
                                    $set('age', $age);
                                }
                            }),
                        TextInput::make('age')
                            ->label('Umur')
                            ->live()
                            ->readOnly(),
                        Select::make('gender')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->label('Jenis Kelamin'),
                        Select::make('religion')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katholik' => 'Katholik',
                                'Budha' => 'Budha',
                                'Hindu' => 'Hindu',
                            ])
                            ->label('Agama'),

                        TextInput::make('address')
                            ->label('Alamat')
                            ->maxLength(255),
                        Select::make('blood_type')
                            ->label('Golongan Darah')
                            ->options([
                                'a' => 'A',
                                'b' => 'B',
                                'c' => 'C',
                                'd' => 'D',
                                'o' => 'O',
                            ])
                            ->searchable(),
                        Select::make('marital_status')
                            ->label('Status Menikah')
                            ->options([
                                'Menikah' => 'Menikah',
                                'Belum Menikah' => 'Belum Menikah',
                            ]),
                        TextInput::make('phone_number')
                            ->label('Nomor Telp')
                            ->tel()
                            ->prefix('+62')
                            ->maxLength(13),
                        TextInput::make('id_number')
                            ->label('Nomor NIK')
                            ->numeric()
                            ->maxLength(16),
                        TextInput::make('familycard_number')
                            ->label('Nomor KK')
                            ->numeric()
                            ->maxLength(16),
                        TextInput::make('npwp_number')
                            ->label('Nomor NPWP')
                            ->numeric()
                            ->maxLength(16),
                        TextInput::make('bank_account_number')
                            ->label('Nomor Rekening')
                            ->numeric()
                            ->maxLength(17),
                        TextInput::make('bpjs_tk_number')
                            ->label('Nomor BPJS TK')
                            ->numeric()
                            ->maxLength(16),
                        TextInput::make('bpjs_kes_number')
                            ->label('Nomor BPJS Kesehatan')
                            ->numeric()
                            ->maxLength(13),
                        TextInput::make('rek_dplk_pribadi')
                            ->label('Rekening DPLK Pribadi')
                            ->numeric()
                            ->maxLength(255),
                        TextInput::make('rek_dplk_bersama')
                            ->label('Rekening DPLK Bersama')
                            ->numeric()
                            ->maxLength(255),
                    ])->columns(3),


                Forms\Components\Section::make('Form Info Kepegawaian')
                    ->description('Form Info Kepegawaiaan')
                    ->collapsed(false)
                    ->schema([
                        DatePicker::make('entry_date')
                            ->label('Tanggal Masuk'), // New field: entry date
                        DatePicker::make('probation_appointment_date')
                            ->label('Pengangkatan Capeg')
                            ->live()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state) {
                                    $dateOfAppointment = Carbon::createFromFormat('Y-m-d', $state);
                                    $lengthService = $dateOfAppointment->diffInYears(Carbon::now());
                                    $set('length_service', $lengthService);
                                }
                            }),
                        TextInput::make('length_service')
                            ->label('Masa Kerja')
                            ->live()
                            ->readOnly(),
                        DatePicker::make('retirement')
                            ->label('Tahun Pensiun'),
                        Select::make('employment_status_id')
                            ->relationship('employmentStatus', 'name')
                            ->label('Status')
                            ->searchable()
                            ->preload(),
                        Select::make('employee_agreement_id')
                            ->relationship('employeeAgreement', 'name')
                            ->label('Perjanjian Kontrak')
                            ->searchable()
                            ->preload(),
                        Select::make('employee_education_id')
                            ->relationship('employeeEducation', 'name')
                            ->label('Pendidikan Terakhir')
                            ->searchable()
                            ->preload(),
                        Select::make('employee_grade_id')
                            ->relationship('employeeGrade', 'name')
                            ->label('Golongan')
                            ->searchable()
                            ->preload(),
                        Select::make('employee_position_id')
                            ->relationship('employeePosition', 'name')
                            ->label('Jabatan')
                            ->searchable()
                            ->preload(),
                        Select::make('departments_id')
                            ->relationship('employeeDepartments', 'name')
                            ->label('Bagian')
                            ->searchable()
                            ->preload(),
                        Select::make('sub_department_id')
                            ->relationship('employeesubDepartments', 'name')
                            ->label('Sub Bagian')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Form Akun Pegawai')
                    ->description('Form Akun Pegawai')
                    ->collapsed(false)
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->label('Username')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('E-Mail')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('image')
                            ->directory('Pegawai')
                            ->label('Foto')
                            ->image(),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto'),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('nippam')
                    ->label('Nippam')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('place_birth')
                    ->label('Tempat Lahir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('date_birth')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('religion')
                    ->label('Agama')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('age')
                    ->label('Umur')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('blood_type')
                    ->label('Golongan Darah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('marital_status')
                    ->label('Status Menikah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('No Telp')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('id_number')
                    ->label('Nomor KTP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('familycard_number')
                    ->label('Nomor KK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('npwp_number')
                    ->label('Nomor NPWP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('bank_account_number')
                    ->label('Nomor Rekening')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('bpjs_tk_number')
                    ->label('Rekening BPJS TK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('bpjs_kes_number')
                    ->label('Rekening BPJS Kesehatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('rek_dplk_pribadi')
                    ->label('Rekening DPLK Pribadi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('rek_dplk_bersama')
                    ->label('Rekening DPLK Bersama')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Tanggal Masuk')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('probation_appointment_date')
                    ->label('Tanggal Calon Pegawai')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('length_service')
                    ->label('Masa Kerja')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('retirement')
                    ->label('Tahun Pensiun')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('employmentStatus.name')
                    ->label('Status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('employeeAgreement.name')
                    ->label('Kontrak Kerja')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('employeeEducation.name')
                    ->label('Pendidikan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('employeeGrade.name')
                    ->label('Golongan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('employeePosition.name')
                    ->label('Jabatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('employeeDepartments.name')
                    ->label('Bagian')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('employeesubDepartments.name')
                    ->label('Sub Bagian')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployees::route('/create'),
            'edit' => Pages\EditEmployees::route('/{record}/edit'),
        ];
    }
}
