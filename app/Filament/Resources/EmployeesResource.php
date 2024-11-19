<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Employees;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use App\Models\MasterEmployeeGrade;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\fileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeesResource\Pages;
use App\Filament\Resources\EmployeesResource\RelationManagers;

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
                                'required' => 'Nama Pegawai wajib diisi',
                                'max' => 'Nama Pegawai tidak boleh melebihi 255 karakter'
                            ]),
                        TextInput::make('place_birth')
                            ->label('Tempat Lahir')
                            ->required()
                            ->maxLength(255)
                            ->rules(['required', 'max:255'])
                            ->validationMessages([
                                'required' => 'Tempat Lahir wajib diisi',
                                'max' => 'Tempat Lahir tidak boleh melebihi 255 karakter'
                            ]),
                        DatePicker::make('date_birth')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state) {
                                    $dateOfBirth = Carbon::createFromFormat('Y-m-d', $state);
                                    $age = $dateOfBirth->age;
                                    $set('age', $age);
                                }
                            })
                            ->validationMessages(['required' => 'Tanggal Lahir wajib diisi']),
                        TextInput::make('age')
                            ->label('Umur')
                            ->live()
                            ->readOnly(),
                        Select::make('gender')
                            ->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])
                            ->label('Jenis Kelamin')
                            ->required()
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
                            ->required()
                            ->validationMessages(['required' => 'Agama wajib dipilih']),
                        TextInput::make('address')
                            ->label('Alamat')
                            ->required()
                            ->maxLength(255)
                            ->rules(['required', 'max:255'])
                            ->validationMessages([
                                'required' => 'Alamat wajib diisi',
                                'max' => 'Alamat tidak boleh melebihi 255 karakter'
                            ]),
                        Select::make('employee_education_id')
                            ->relationship('employeeEducation', 'name')
                            ->label('Pendidikan Terakhir')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationMessages(['required' => 'Pendidikan Terakhir wajib dipilih']),
                        Select::make('blood_type')
                            ->label('Golongan Darah')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'AB' => 'AB',
                                'O' => 'O',
                            ])
                            ->searchable()
                            ->required()
                            ->validationMessages(['required' => 'Golongan Darah wajib dipilih']),

                        Select::make('marital_status')
                            ->label('Status Pernikahan')
                            ->options([
                                'Menikah' => 'Menikah',
                                'Belum Menikah' => 'Belum Menikah',
                            ])
                            ->required()
                            ->validationMessages(['required' => 'Status Pernikahan wajib dipilih']),

                        TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required()
                            ->validationMessages(['required' => 'Nomor Telepon wajib diisi']),

                        TextInput::make('id_number')
                            ->label('NIK')
                            ->required()
                            ->maxLength(16)
                            ->validationMessages(['required' => 'NIK wajib diisi']),

                        TextInput::make('familycard_number')
                            ->label('Nomor KK')
                            ->required()
                            ->maxLength(16)
                            ->validationMessages(['required' => 'Nomor KK wajib diisi']),

                        TextInput::make('npwp_number')
                            ->label('NPWP')
                            ->required()
                            ->validationMessages(['required' => 'NPWP wajib diisi']),

                        TextInput::make('bank_account_number')
                            ->label('Nomor Rekening')
                            ->required()
                            ->validationMessages(['required' => 'Nomor Rekening wajib diisi']),

                        TextInput::make('bpjs_tk_number')
                            ->label('BPJS TK')
                            ->required()
                            ->validationMessages(['required' => 'BPJS TK wajib diisi']),

                        TextInput::make('bpjs_kes_number')
                            ->label('BPJS Kesehatan')
                            ->required()
                            ->validationMessages(['required' => 'BPJS Kesehatan wajib diisi']),

                        TextInput::make('rek_dplk_pribadi')
                            ->label('DPLK Pribadi')
                            ->required()
                            ->validationMessages(['required' => 'DPLK Pribadi wajib diisi']),

                        TextInput::make('rek_dplk_bersama')
                            ->label('DPLK Bersama')
                            ->required()
                            ->validationMessages(['required' => 'DPLK Bersama wajib diisi']),
                    ])->columns(3),

                Section::make('Form Info Kepegawaian')
                    ->description('Form Info Kepegawaiaan')
                    ->schema([
                        DatePicker::make('entry_date')
                            ->label('Tanggal Masuk')
                            ->required()
                            ->validationMessages(['required' => 'Tanggal Masuk wajib diisi']),

                        Select::make('employment_status_id')
                            ->relationship('employmentStatus', 'name')
                            ->label('Status')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationMessages(['required' => 'Status wajib dipilih']),

                        Select::make('master_employee_agreement_id')
                            ->relationship('employeeAgreement', 'name')
                            ->label('Kontrak Kerja')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationMessages(['required' => 'Kontrak Kerja wajib dipilih']),

                        DatePicker::make('agreement_date_start')
                            ->label('Tanggal Mulai Perjanjian Kontrak')
                            ->required()
                            ->validationMessages(['required' => 'Tanggal Mulai Perjanjian Kontrak wajib diisi']),

                        DatePicker::make('agreement_date_end')
                            ->label('Tanggal Akhir Perjanjian Kontrak')
                            ->required()
                            ->validationMessages(['required' => 'Tanggal Akhir Perjanjian Kontrak wajib diisi']),

                        Select::make('basic_salary_id')
                            ->relationship('basicSalary', function ($query) {
                                return $query->with(['employeeGrade', 'serviceGrade'])
                                    ->select('master_employee_basic_salary.*')
                                    ->join('master_employee_grade', 'master_employee_basic_salary.employee_grade_id', '=', 'master_employee_grade.id')
                                    ->join('master_employee_service_grade', 'master_employee_basic_salary.employee_service_grade_id', '=', 'master_employee_service_grade.id')
                                    ->orderBy('master_employee_grade.name')
                                    ->orderBy('master_employee_service_grade.service_grade');
                            })
                            ->getOptionLabelFromRecordUsing(
                                fn($record) =>
                                "Golongan: {$record->employeeGrade->name} | MKG: {$record->serviceGrade->service_grade} - Gaji Pokok: Rp " .
                                    number_format($record->amount, 0, ',', '.')
                            )
                            ->label('Golongan dan Gaji Pokok')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationMessages([
                                'required' => 'Golongan dan Gaji Pokok wajib dipilih'
                            ]),

                        DatePicker::make('grade_date_start')
                            ->label('Tanggal Mulai Golongan')
                            ->required()
                            ->validationMessages(['required' => 'Tanggal Mulai Golongan wajib diisi']),

                        DatePicker::make('grade_date_end')
                            ->label('Tanggal Akhir Golongan')
                            ->required()
                            ->validationMessages(['required' => 'Tanggal Akhir Golongan wajib diisi']),

                        DatePicker::make('periodic_salary_date_start')
                            ->label('Tanggal Awal Berkala')
                            ->required()
                            ->validationMessages(['required' => 'Tanggal Awal Berkala wajib diisi']),

                        DatePicker::make('periodic_salary_date_end')
                            ->label('Tanggal Akhir Berkala')
                            ->required()
                            ->validationMessages(['required' => 'Tanggal Akhir Berkala wajib diisi']),

                        Select::make('employee_position_id')
                            ->relationship('employeePosition', 'name')
                            ->label('Jabatan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationMessages(['required' => 'Jabatan wajib dipilih']),

                        Select::make('departments_id')
                            ->relationship('EmployeeDepartments', 'name')
                            ->label('Bagian')
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('sub_department_id', null))
                            ->required()
                            ->validationMessages(['required' => 'Bagian wajib dipilih']),

                        Select::make('sub_department_id')
                            ->relationship('EmployeeSubDepartments', 'name', fn(Builder $query, callable $get) => $query
                                ->when($get('departments_id'), fn(Builder $q, $departmentId) => $q
                                    ->where('departments_id', $departmentId)))
                            ->label('Sub Bagian')
                            ->disabled(fn(callable $get) => !$get('departments_id'))
                            ->required()
                            ->validationMessages(['required' => 'Sub Bagian wajib dipilih']),
                    ])->columns(2),
                Section::make('Form Akun')
                    ->schema([
                        TextInput::make('username')
                            ->required()
                            ->validationMessages(['required' => 'Username wajib diisi']),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->validationMessages(['required' => 'Email wajib diisi']),

                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->validationMessages(['required' => 'Password wajib diisi']),

                        FileUpload::make('image')
                            ->image()
                            ->directory('employees')
                            ->label('Foto'),
                    ])->columns(2),

                Forms\Components\Hidden::make('users_id')
                    ->default(auth()->id())
                    ->rules('required|exists:users,id'),
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
                Tables\Columns\TextColumn::make('nippam')
                    ->label('NIPPAM')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('place_birth')
                    ->label('Tempat Lahir')
                    ->searchable(),

                Tables\Columns\TextColumn::make('date_birth')
                    ->label('Tanggal Lahir')
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->searchable(),

                Tables\Columns\TextColumn::make('age')
                    ->label('Umur')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('religion')
                    ->label('Agama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat')
                    ->searchable(),

                Tables\Columns\TextColumn::make('employeeEducation.name')
                    ->label('Pendidikan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('blood_type')
                    ->label('Golongan Darah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('marital_status')
                    ->label('Status Pernikahan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Nomor Telepon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('id_number')
                    ->label('NIK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('familycard_number')
                    ->label('Nomor KK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('npwp_number')
                    ->label('NPWP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('bank_account_number')
                    ->label('Nomor Rekening')
                    ->searchable(),

                Tables\Columns\TextColumn::make('bpjs_tk_number')
                    ->label('BPJS TK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('bpjs_kes_number')
                    ->label('BPJS Kesehatan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rek_dplk_pribadi')
                    ->label('DPLK Pribadi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rek_dplk_bersama')
                    ->label('DPLK Bersama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Tanggal Masuk')
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('employmentStatus.name')
                    ->label('Status')
                    ->searchable(),

                Tables\Columns\TextColumn::make('employeeAgreement.name')
                    ->label('Kontrak Kerja')
                    ->searchable(),

                Tables\Columns\TextColumn::make('agreement_date_start')
                    ->label('Mulai Kontrak')
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('agreement_date_end')
                    ->label('Akhir Kontrak')
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('basicSalary.employeeGrade.name')
                    ->label('Golongan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('basicSalary.serviceGrade.service_grade')
                    ->label('MKG')
                    ->searchable(),

                Tables\Columns\TextColumn::make('basicSalary.amount')
                    ->label('Gaji Pokok')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('grade_date_start')
                    ->label('Mulai Golongan')
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('grade_date_end')
                    ->label('Akhir Golongan')
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('periodic_salary_date_start')
                    ->label('Mulai Berkala')
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('periodic_salary_date_end')
                    ->label('Akhir Berkala')
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('employeePosition.name')
                    ->label('Jabatan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('employeeDepartments.name')
                    ->label('Bagian')
                    ->searchable(),

                Tables\Columns\TextColumn::make('employeeSubDepartments.name')
                    ->label('Sub Bagian')
                    ->searchable(),

                Tables\Columns\TextColumn::make('username')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
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
                Tables\Filters\Filter::make('Golongan')
                    ->form([
                        Select::make('basic_salary_id')
                            ->relationship('basicSalary', function ($query) {
                                return $query->with(['employeeGrade', 'serviceGrade'])
                                    ->select('master_employee_basic_salary.*')
                                    ->join('master_employee_grade', 'master_employee_basic_salary.employee_grade_id', '=', 'master_employee_grade.id')
                                    ->join('master_employee_service_grade', 'master_employee_basic_salary.employee_service_grade_id', '=', 'master_employee_service_grade.id')
                                    ->orderBy('master_employee_grade.name')
                                    ->orderBy('master_employee_service_grade.service_grade');
                            })
                            ->getOptionLabelFromRecordUsing(
                                fn($record) =>
                                "Golongan: {$record->employeeGrade->name} | MKG: {$record->serviceGrade->service_grade}"
                            )
                            ->label('Golongan dan MKG')
                            ->searchable()
                            ->preload()
                            ->columnSpan(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['basic_salary_id'])) {
                            $query->whereHas('basicSalary', function (Builder $q) use ($data) {
                                $q->where('id', $data['basic_salary_id']);
                            });
                        }
                    }),

                Tables\Filters\Filter::make('Status')
                    ->form([
                        Select::make('employment_status_id')
                            ->relationship('employmentStatus', 'name')
                            ->label('Status')
                            ->searchable()
                            ->preload()
                            ->columnSpan(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['employment_status_id'])) {
                            $query->whereHas('employmentStatus', function (Builder $q) use ($data) {
                                $q->where('id', $data['employment_status_id']);
                            });
                        }
                    }),

                Tables\Filters\Filter::make('Jabatan')
                    ->form([
                        Select::make('employee_position_id')
                            ->relationship('employeePosition', 'name')
                            ->label('Jabatan')
                            ->searchable()
                            ->preload()
                            ->columnSpan(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['employee_position_id'])) {
                            $query->whereHas('employeePosition', function (Builder $q) use ($data) {
                                $q->where('id', $data['employee_position_id']);
                            });
                        }
                    }),

                Tables\Filters\Filter::make('Kontrak Kerja')
                    ->form([
                        Select::make('master_employee_agreement_id')
                            ->relationship('employeeAgreement', 'name')
                            ->label('Kontrak Kerja')
                            ->searchable()
                            ->preload()
                            ->columnSpan(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['master_employee_agreement_id'])) {
                            $query->whereHas('employeeAgreement', function (Builder $q) use ($data) {
                                $q->where('id', $data['master_employee_agreement_id']);
                            });
                        }
                    }),

                Tables\Filters\Filter::make('Bagian')
                    ->form([
                        Select::make('departments_id')
                            ->relationship('EmployeeDepartments', 'name')
                            ->label('Bagian')
                            ->searchable()
                            ->preload()
                            ->columnSpan(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['departments_id'])) {
                            $query->whereHas('EmployeeDepartments', function (Builder $q) use ($data) {
                                $q->where('id', $data['departments_id']);
                            });
                        }
                    }),

                Tables\Filters\Filter::make('Sub Bagian')
                    ->form([
                        Select::make('sub_department_id')
                            ->relationship('EmployeeSubDepartments', 'name', fn(Builder $query, callable $get) => $query
                                ->when($get('departments_id'), fn(Builder $q, $departmentId) => $q
                                    ->where('departments_id', $departmentId)))
                            ->label('Sub Bagian')
                            ->disabled(fn(callable $get) => !$get('departments_id'))
                            ->searchable()
                            ->preload()
                            ->columnSpan(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['sub_department_id'])) {
                            $query->whereHas('EmployeeSubDepartments', function (Builder $q) use ($data) {
                                $q->where('id', $data['sub_department_id']);
                            });
                        }
                    }),
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
            'view' => Pages\ViewEmployees::route('/{record}'),
        ];
    }
}
