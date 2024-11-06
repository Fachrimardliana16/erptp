<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeesResource\Pages;
use App\Filament\Resources\EmployeesResource\RelationManagers;
use App\Models\Employees;
use App\Models\MasterEmployeeGrade;
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

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
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
                            ->maxLength(255)
                            ->rules(['max:255'])
                            ->validationMessages(['max' => 'NIPPAM tidak boleh melebihi 255 karakter']),
                        TextInput::make('name')
                            ->label('Nama Pegawai')
                            ->required()
                            ->maxLength(255)
                            ->rules(['required', 'max:255'])
                            ->validationMessages([
                                '
                            required' => 'Nama Pegawai wajib diisi',
                                'max' => 'Nama Pegawai tidak boleh melebihi 255 karakter'
                            ]),
                        TextInput::make('place_birth')
                            ->label('Tempat Lahir')
                            ->maxLength(255)
                            ->rules(['max:255'])
                            ->validationMessages(['max' => 'Tempat Lahir tidak boleh melebihi 255 karakter']),
                        DatePicker::make('date_birth')
                            ->label('Tanggal Lahir')
                            ->live()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state) {
                                    $dateOfBirth = Carbon::createFromFormat('Y-m-d', $state);
                                    $age = $dateOfBirth->age;
                                    $set('age', $age);
                                }
                            })
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Tanggal Lahir wajib diisi']),
                        TextInput::make('age')
                            ->label('Umur')
                            ->live()
                            ->readOnly(),
                        Select::make('gender')
                            ->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan',])
                            ->label('Jenis Kelamin')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Jenis Kelamin wajib dipilih']),
                        Select::make('religion')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katholik' => 'Katholik',
                                'Budha' => 'Budha',
                                'Hindu' => 'Hindu',
                            ])
                            ->label('Agama')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Agama wajib dipilih']),
                        TextInput::make('address')
                            ->label('Alamat')
                            ->maxLength(255)
                            ->rules(['max:255'])
                            ->validationMessages(['max' => 'Alamat tidak boleh melebihi 255 karakter']),
                        Select::make('blood_type')
                            ->label('Golongan Darah')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'AB' => 'AB',
                                'O' => 'O',
                            ])
                            ->searchable()
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Golongan Darah wajib dipilih']),
                        Select::make('marital_status')
                            ->label('Status Menikah')
                            ->options([
                                'Menikah' => 'Menikah',
                                'Belum Menikah' => 'Belum Menikah',
                            ])
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Status Menikah wajib dipilih']),
                        TextInput::make('phone_number')
                            ->label('Nomor Telp')
                            ->tel()
                            ->prefix('+62')
                            ->maxLength(13)
                            ->rules(['required', 'max:13', 'regex:/^\+628[1-9][0-9]{6,9}$/'])
                            ->validationMessages([
                                'required' => 'Nomor Telp wajib diisi',
                                'max' => 'Nomor Telp tidak boleh melebihi 13 karakter',
                                'regex' => 'Nomor Telp wajib berformat +62'
                            ]),
                        Select::make('employee_education_id')
                            ->relationship('employeeEducation', 'name')
                            ->label('Pendidikan Terakhir')
                            ->searchable()
                            ->preload()
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Pendidikan Terakhir wajib dipilih']),
                        TextInput::make('id_number')
                            ->label('Nomor NIK')
                            ->numeric()
                            ->maxLength(16)
                            ->helperText('Isikan nomor KTP Anda, pastikan terdiri dari 16 digit.')
                            ->rules(['required', 'digits:16'])
                            ->validationMessages([
                                'required' => 'Nomor NIK wajib diisi',
                                'digits' => 'Nomor NIK harus berupa angka 16 digit'
                            ]),
                        TextInput::make('familycard_number')
                            ->label('Nomor KK')
                            ->numeric()
                            ->maxLength(16)
                            ->helperText('Isikan nomor KK Anda, pastikan terdiri dari 16 digit.')
                            ->rules(['required', 'digits:16'])
                            ->validationMessages([
                                'required' => 'Nomor KK wajib diisi',
                                'digits' => 'Nomor KK harus berupa angka 16 digit'
                            ]),
                        TextInput::make('npwp_number')
                            ->label('Nomor NPWP')
                            ->numeric()
                            ->maxLength(20)
                            ->helperText('Isikan nomor NPWP Anda, pastikan terdiri dari 16 digit.')
                            ->rules(['required', 'digits_between:15,20'])
                            ->validationMessages([
                                'required' => 'Nomor NPWP wajib diisi',
                                'digits_between' => 'Nomor NPWP harus berupa angka hingga 20 digit'
                            ]),
                        TextInput::make('bank_account_number')
                            ->label('Nomor Rekening')
                            ->numeric()
                            ->maxLength(17)
                            ->rules(['required', 'digits_between:1,17'])
                            ->validationMessages([
                                'required' => 'Nomor Rekening wajib diisi',
                                'digits_between' => 'Nomor Rekening harus berupa angka hingga 17 digit'
                            ]),
                        TextInput::make('bpjs_tk_number')
                            ->label('Nomor BPJS TK')
                            ->numeric()
                            ->maxLength(16)
                            ->rules(['required', 'digits:16'])
                            ->validationMessages([
                                'required' => 'Nomor BPJS TK wajib diisi',
                                'digits' => 'Nomor BPJS TK harus berupa angka 16 digit'
                            ]),
                        TextInput::make('bpjs_kes_number')
                            ->label('Nomor BPJS Kesehatan')
                            ->numeric()
                            ->maxLength(13)
                            ->rules(['required', 'digits:13'])
                            ->validationMessages([
                                'required' => 'Nomor BPJS Kesehatan wajib diisi',
                                'digits' => 'Nomor BPJS Kesehatan harus berupa angka 13 digit'
                            ]),
                        TextInput::make('rek_dplk_pribadi')
                            ->label('Rekening DPLK Pribadi')
                            ->numeric()
                            ->maxLength(255)
                            ->rules(['required', 'max:255'])
                            ->validationMessages([
                                'required' => 'Rekening DPLK Pribadi wajib diisi',
                                'max' => 'Rekening DPLK Pribadi tidak boleh melebihi 255 karakter'
                            ]),
                        TextInput::make('rek_dplk_bersama')
                            ->label('Rekening DPLK Bersama')
                            ->numeric()
                            ->maxLength(255)
                            ->rules(['required', 'max:255'])
                            ->validationMessages([
                                'required' => 'Rekening DPLK Bersama wajib diisi',
                                'max' => 'Rekening DPLK Bersama tidak boleh melebihi 255 karakter'
                            ]),
                    ])->columns(3),


                Section::make('Form Info Kepegawaian')
                    ->description('Form Info Kepegawaiaan')
                    ->collapsed(false)
                    ->schema([
                        DatePicker::make('entry_date')
                            ->label('Tanggal Masuk')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Tanggal Masuk wajib diisi']),
                        DatePicker::make('probation_appointment_date')
                            ->label('Pengangkatan Capeg')
                            ->live()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state) {
                                    $dateOfAppointment = Carbon::createFromFormat('Y-m-d', $state);
                                    $lengthService = $dateOfAppointment
                                        ->diffInYears(Carbon::now());
                                    $set('length_service', $lengthService);
                                }
                            })
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Pengangkatan Capeg wajib diisi']),
                        TextInput::make('length_service')
                            ->label('Masa Kerja')
                            ->live()
                            ->readOnly(),
                        DatePicker::make('retirement')
                            ->label('Tahun Pensiun')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Tahun Pensiun wajib diisi']),
                        Select::make('employment_status_id')
                            ->relationship('employmentStatus', 'name')
                            ->label('Status')
                            ->searchable()
                            ->preload()
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Status wajib dipilih']),
                        Select::make('master_employee_agreement_id')
                            ->relationship('employeeAgreement', 'name')
                            ->label('Kontrak Kerja')
                            ->searchable()
                            ->preload()
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Kontrak Kerja wajib dipilih']),
                        DatePicker::make('agreement_date_start')
                            ->label('Tanggal Mulai Perjanjian Kontrak')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Tanggal Mulai Perjanjian Kontrak wajib diisi']),
                        DatePicker::make('agreement_date_end')
                            ->label('Tanggal Akhir Perjanjian Kontrak')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Tanggal Akhir Perjanjian Kontrak wajib diisi']),
                        Select::make('employee_grade_id')
                            ->relationship('employeeGrade', 'name')
                            ->label('Golongan')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Golongan wajib dipilih']),
                        TextInput::make('basic_salary')
                            ->label('Gaji Pokok')
                            ->prefix('Rp. ')
                            ->readOnly()
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Gaji Pokok wajib diisi']),
                        DatePicker::make('grade_date_start')
                            ->label('Tanggal Mulai Golongan')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Tanggal Mulai Golongan wajib diisi']),
                        DatePicker::make('grade_date_end')
                            ->label('Tanggal Akhir Golongan')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Tanggal Akhir Golongan wajib diisi']),
                        TextInput::make('amount')
                            ->label('Total Berkala')
                            ->prefix('Rp. ')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Total Berkala wajib diisi']),
                        DatePicker::make('periodic_salary_date_start')
                            ->label('Tanggal Awal Berkala')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Tanggal Awal Berkala wajib diisi']),
                        DatePicker::make('periodic_salary_date_end')
                            ->label('Tanggal Akhir Berkala')
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Tanggal Akhir Berkala wajib diisi']),
                        Select::make('employee_position_id')
                            ->relationship('employeePosition', 'name')
                            ->label('Jabatan')
                            ->searchable()
                            ->preload()
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Jabatan wajib dipilih']),
                        Select::make('departments_id')
                            ->relationship('EmployeeDepartments', 'name')
                            ->label('Bagian')
                            ->live() // Make it live to trigger updates 
                            ->afterStateUpdated(fn(callable $set) => $set('sub_department_id', null)) // Reset sub department when department changes 
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Bagian wajib dipilih']),
                        Select::make('sub_department_id')
                            ->relationship('EmployeeSubDepartments', 'name', fn(Builder $query, callable $get) => $query
                                ->when($get('departments_id'), fn(Builder $q, $departmentId) => $q
                                    ->where('departments_id', $departmentId)))->label('Sub Bagian')
                            ->disabled(fn(callable $get) => ! $get('departments_id')) // Disable until department is selected 
                            ->rules(['required'])
                            ->validationMessages(['required' => 'Sub Bagian wajib dipilih']),
                    ])->columns(2),

                Section::make('Form Akun Pegawai')
                    ->description('Form Akun Pegawai')
                    ->collapsed(false)
                    ->schema([
                        TextInput::make('username')
                            ->label('Username')
                            ->maxLength(255)
                            ->rules(['required', 'max:255'])
                            ->validationMessages(['required' => 'Username wajib diisi', 'max' => 'Username tidak boleh melebihi 255 karakter']),
                        TextInput::make('email')
                            ->label('E-Mail')
                            ->email()
                            ->maxLength(255)
                            ->rules(['required', 'email', 'max:255'])
                            ->validationMessages([
                                'required' => 'E-Mail wajib diisi',
                                'email' => 'E-Mail harus berupa format email yang valid',
                                'max' => 'E-Mail tidak boleh melebihi 255 karakter'
                            ]),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->maxLength(255)
                            ->rules(['required', 'min:8', 'max:255'])
                            ->validationMessages([
                                'required' => 'Password wajib diisi',
                                'min' => 'Password tidak boleh kurang dari 8 karakter',
                                'max' => 'Password tidak boleh melebihi 255 karakter'
                            ]),
                        FileUpload::make('image')
                            ->directory('Pegawai')
                            ->label('Foto')
                            ->image()
                            ->rules(['image', 'mimes:jpg,jpeg,png', 'max:2048'])
                            ->validationMessages([
                                'image' => 'File harus berupa gambar',
                                'mimes' => 'Foto harus dalam format jpg, jpeg, atau png',
                                'max' => 'Ukuran foto tidak boleh lebih dari 2MB'
                            ]),
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
                Tables\Columns\TextColumn::make('employeeGrade.name')
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
