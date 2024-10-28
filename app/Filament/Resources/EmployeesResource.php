<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeesResource\Pages;
use App\Filament\Resources\EmployeesResource\RelationManagers;
use App\Models\Employees;
use App\Models\MasterEmployeeBasicSalary;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\fileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeesResource extends Resource
{
    protected static ?string $model = Employees::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?int $navigationSort = 3;

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
                                'A' => 'A',
                                'B' => 'B',
                                'AB' => 'AB',
                                'O' => 'O',
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
                            ->maxLength(20),
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

                Section::make('Form Info Kepegawaian')
                    ->description('Form Info Kepegawaiaan')
                    ->collapsed(false)
                    ->schema([
                        DatePicker::make('entry_date')
                            ->label('Tanggal Masuk'),
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
                        Select::make('master_employee_agreement_id')
                            ->relationship('employeeAgreement', 'name')
                            ->label('Kontrak Kerja')
                            ->searchable()
                            ->preload(),
                        DatePicker::make('agreement_date_start')
                            ->label('Tanggal Mulai Perjanjian Kontrak'),
                        DatePicker::make('agreement_date_end')
                            ->label('Tanggal Akhir Perjanjian Kontrak'),
                        Select::make('employee_education_id')
                            ->relationship('employeeEducation', 'name')
                            ->label('Pendidikan Terakhir')
                            ->searchable()
                            ->preload(),
                        Select::make('basic_salary_id')
                            ->options(MasterEmployeeBasicSalary::query()->pluck('name', 'id'))
                            ->afterStateUpdated(function ($set, $state) {
                                $data = MasterEmployeeBasicSalary::find($state);
                                if ($data) {
                                    $set('basic_salary', $data->amount);
                                }
                            })
                            ->label('Golongan')
                            ->searchable()
                            ->preload()
                            ->live(),
                        Hidden::make('basic_salary_id')
                            ->default(function ($get) {
                                $basic_salary_id = $get('basic_salary_id');
                                return $basic_salary_id;
                            }),
                        TextInput::make('basic_salary')
                            ->label('Gaji Pokok')
                            ->prefix('Rp. ')
                            ->readOnly(),
                        Hidden::make('basic_salary')
                            ->default(function ($get) {
                                return $get('basic_salary');
                            }),
                        DatePicker::make('grade_date_start')
                            ->label('Tanggal Mulai Golongan'),
                        DatePicker::make('grade_date_end')
                            ->label('Tanggal Akhir Golongan'),
                        TextInput::make('amount')
                            ->label('Total Berkala')
                            ->prefix('Rp. '),
                        DatePicker::make('periodic_salary_date_start')
                            ->label('Tanggal Awal Berkala'),
                        DatePicker::make('periodic_salary_date_end')
                            ->label('Tanggal Akhir Berkala'),
                        Select::make('employee_position_id')
                            ->relationship('employeePosition', 'name')
                            ->label('Jabatan')
                            ->searchable()
                            ->preload(),
                        Select::make('departments_id')
                            ->relationship('EmployeeDepartments', 'name')
                            ->label('Bagian')
                            ->required()
                            ->live() // Make it live to trigger updates
                            ->afterStateUpdated(fn(callable $set) => $set('sub_department_id', null)) // Reset sub department when department changes
                            ->validationAttribute('Bagian'),

                        Select::make('sub_department_id')
                            ->relationship(
                                'EmployeeSubDepartments',
                                'name',
                                fn(Builder $query, callable $get) => $query
                                    ->when(
                                        $get('departments_id'),
                                        fn(Builder $q, $departmentId) => $q->where('departments_id', $departmentId)
                                    )
                            )
                            ->label('Sub Bagian')
                            ->required()
                            ->disabled(fn(callable $get) => ! $get('departments_id')) // Disable until department is selected
                            ->validationAttribute('Sub Bagian'),

                    ])->columns(2),

                Section::make('Form Akun Pegawai')
                    ->description('Form Akun Pegawai')
                    ->collapsed(false)
                    ->schema([
                        TextInput::make('username')
                            ->label('Username')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('E-Mail')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->maxLength(255),
                        FileUpload::make('image')
                            ->directory('Pegawai')
                            ->label('Foto')
                            ->image(),
                        Hidden::make('users_id')
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
                Tables\Columns\TextColumn::make('employeeEducation.name')
                    ->label('Pendidikan')
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
                Tables\Columns\TextColumn::make('agreement_date_start')
                    ->label('Tanggal Mulai Perjanjian Kontrak')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('agreement_date_end')
                    ->label('Tanggal Akhir Perjanjian Kontrak')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('employeeBasic.name')
                    ->label('Golongan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('grade_date_start')
                    ->label('Tanggal Mulai Golongan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('grade_date_end')
                    ->label('Tanggal Akhir Golongan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('basic_salary')
                    ->Money('Rp. ')
                    ->label('Gaji Pokok')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('periodic_salary_date_start')
                    ->label('Tanggal Mulai Berkala')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('periodic_salary_date_end')
                    ->label('Tanggal Akhir Berkala')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('amount')
                    ->Money('Rp. ')
                    ->label('Berkala')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('place_birth')
                    ->label('Tempat Lahir')
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
