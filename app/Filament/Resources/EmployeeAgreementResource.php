<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeAgreementResource\Pages;
use App\Models\EmployeeAgreement;
use App\Models\EmployeeJobApplicationArchives;
use App\Models\Employees;
use App\Models\MasterEmployeeBasicSalary;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Closure;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;

class EmployeeAgreementResource extends Resource
{
    protected static ?string $model = EmployeeAgreement::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
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
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->validationAttribute('Nomor Perjanjian Kontrak')
                            ->rules(['string', 'max:255', 'regex:/^[A-Za-z0-9\/-]+$/'])
                            ->placeholder('Contoh: PK/2024/001')
                            ->validationMessages([
                                'required' => 'Nomor Perjanjian Kontrak wajib diisi',
                                'max' => 'Nomor Perjanjian Kontrak tidak boleh lebih dari 255 karakter',
                                'unique' => 'Nomor Perjanjian Kontrak sudah digunakan',
                                'regex' => 'Format Nomor Perjanjian Kontrak tidak valid'
                            ]),

                        Select::make('job_application_archives_id')
                            ->options(function () {
                                return EmployeeJobApplicationArchives::query()
                                    ->where('application_status', false)
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
                                    $set('hidden_name', $archive->name);
                                }
                            })
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->label('Nomor Registrasi Lamaran')
                            ->required()
                            ->exists('employee_job_application_archives', 'id')
                            ->validationMessages([
                                'required' => 'Nomor Registrasi Lamaran wajib dipilih',
                                'exists' => 'Nomor Registrasi Lamaran tidak valid'
                            ]),

                        TextInput::make('name')
                            ->label('Nama Pegawai')
                            ->disabled()
                            ->required()
                            ->dehydrated(false),

                        Hidden::make('name')
                            ->label('Nama Pegawai')
                            ->required()
                            ->rules(['string', 'max:255']),

                        Select::make('agreement_id')
                            ->relationship('agreement', 'name')
                            ->label('Status Kontrak')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->exists('master_employee_agreement', 'id')
                            ->validationMessages([
                                'required' => 'Status Kontrak wajib dipilih',
                                'exists' => 'Status Kontrak tidak valid'
                            ]),

                        Select::make('departments_id')
                            ->relationship('agreementDepartement', 'name')
                            ->label('Bagian')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('sub_department_id', null))
                            ->exists('master_departments', 'id')
                            ->validationMessages([
                                'exists' => 'Bagian tidak valid'
                            ]),

                        Select::make('sub_department_id')
                            ->relationship(
                                'agreementSubDepartement',
                                'name',
                                fn(Builder $query, callable $get) => $query
                                    ->when(
                                        $get('departments_id'),
                                        fn(Builder $q, $departmentId) => $q->where('departments_id', $departmentId)
                                    )
                            )
                            ->label('Sub Bagian')
                            ->searchable()
                            ->preload()
                            ->disabled(fn(callable $get) => !$get('departments_id'))
                            ->exists('master_sub_departments', 'id')
                            ->validationMessages([
                                'exists' => 'Sub Bagian tidak valid'
                            ]),

                        Select::make('employee_position_id')
                            ->relationship('agreementPosition', 'name')
                            ->label('Jabatan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->exists('master_employee_position', 'id')
                            ->validationMessages([
                                'required' => 'Jabatan wajib dipilih',
                                'exists' => 'Jabatan tidak valid'
                            ]),

                        Select::make('employment_status_id')
                            ->relationship('agreementStatus', 'name')
                            ->label('Status Pegawai')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->exists('master_employee_status_employement', 'id')
                            ->validationMessages([
                                'required' => 'Status Pegawai wajib dipilih',
                                'exists' => 'Status Pegawai tidak valid'
                            ]),

                        Select::make('basic_salary_id')
                            ->label('Pilih Golongan dan Gaji Pokok')
                            ->options(function () {
                                return MasterEmployeeBasicSalary::query()
                                    ->with('employeeGrade')
                                    ->get()
                                    ->mapWithKeys(function ($basicSalary) {
                                        return [
                                            $basicSalary->id => "Golongan: {$basicSalary->employeeGrade->name} - Gaji Pokok: Rp " . number_format($basicSalary->amount, 0, ',', '.')
                                        ];
                                    });
                            })
                            ->reactive()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->exists('master_employee_basic_salary', 'id')
                            ->validationMessages([
                                'required' => 'Golongan dan Gaji Pokok wajib dipilih',
                                'exists' => 'Golongan dan Gaji Pokok tidak valid'
                            ])
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $basicSalary = MasterEmployeeBasicSalary::with('employeeGrade')->find($state);
                                    $set('grade_id', $basicSalary->employeeGrade->name);
                                    $set('amount', number_format($basicSalary->amount, 0, ',', '.'));
                                } else {
                                    $set('grade_id', null);
                                    $set('amount', null);
                                }
                            }),

                        TextInput::make('grade_id')
                            ->label('Golongan Pegawai')
                            ->disabled(),

                        TextInput::make('amount')
                            ->label('Gaji Pokok')
                            ->disabled(),

                        Group::make()
                            ->schema([
                                DatePicker::make('agreement_date_start')
                                    ->label('Tanggal Mulai Perjanjian')
                                    ->required()
                                    ->rules(['date', 'after_or_equal:today'])
                                    ->validationMessages([
                                        'required' => 'Tanggal Mulai Perjanjian wajib diisi',
                                        'date' => 'Format tanggal tidak valid',
                                        'after_or_equal' =>
                                        'Tanggal Mulai Perjanjian minimal hari ini',
                                    ]),
                                DatePicker::make('agreement_date_end')
                                    ->label('Tanggal Akhir Perjanjian')
                                    ->required()
                                    ->rules(['date', 'after:agreement_date_start'])
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $startDate = $get('agreement_date_start');
                                        if (empty($startDate) || empty($state)) {
                                            return;
                                        }
                                        $startDate = Carbon::parse($startDate);
                                        $endDate = Carbon::parse($state);
                                        if ($startDate->diffInYears($endDate) > 2) {
                                            Notification::make()->title('Error')
                                                ->body('Masa kontrak tidak boleh lebih dari 2 tahun')
                                                ->danger()
                                                ->send();
                                            $set('agreement_date_end', null);
                                        }
                                    })->validationMessages([
                                        'required' => 'Tanggal Akhir Perjanjian wajib diisi',
                                        'date' => 'Format tanggal tidak valid',
                                        'after' => 'Tanggal Akhir harus setelah Tanggal Mulai',
                                    ]),
                            ])
                            ->columns(2),

                        FileUpload::make('docs')
                            ->directory('Perjanjian Kontrak')
                            ->label('Lampiran Dokumen')
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120) // 5MB in kilobytes
                            ->validationMessages([
                                'required' => 'Lampiran Dokumen wajib diunggah',
                                'mimes' => 'File harus berformat PDF',
                                'max' => 'Ukuran file tidak boleh lebih dari 5MB'
                            ])
                            ->helperText('Hanya file dengan format .pdf yang diperbolehkan. Maksimal ukuran file 5MB'),

                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\BulkAction::make('Export Pdf') // Action untuk download PDF yang sudah difilter
                    ->icon('heroicon-m-arrow-down-tray')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        // Ambil data karyawan yang memiliki jabatan 'Kepala Sub Bagian Kepegawaian'
                        $employee = Employees::whereHas('employeePosition', function ($query) {
                            $query->where('name', 'Kepala Sub Bagian Kepegawaian');
                        })->first();

                        // Cek apakah pegawai ditemukan
                        if (!$employee) {
                            // Menampilkan notifikasi kesalahan
                            Notification::make()
                                ->title('Kesalahan')
                                ->danger() // notifikasi kesalahan
                                ->body('Tidak ada pegawai dengan jabatan Kepala Sub Bagian Kepegawaian.')
                                ->persistent() // Notifikasi akan tetap muncul sampai ditutup oleh pengguna
                                ->send();
                            return;
                        }
                        // Render PDF dengan data records dan employee
                        return response()->streamDownload(function () use ($records, $employee) {
                            $pdfContent = Blade::render('pdf.report_employee_agreement', [
                                'records' => $records,
                                'employee' => $employee
                            ]);
                            echo Pdf::loadHTML($pdfContent)
                                ->setPaper('A4', 'landscape') // Set ukuran kertas dan orientasi
                                ->stream();
                        }, 'report_employee_agreement.pdf');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
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
                Tables\Columns\TextColumn::make('basicSalary.employeeGrade.name')
                    ->label('Golongan Pegawai')
                    ->searchable()
                    ->sortable(),

                // Menampilkan amount dengan format currency
                Tables\Columns\TextColumn::make('basicSalary.amount')
                    ->label('Gaji Pokok')
                    ->money('idr') // Format sebagai mata uang IDR
                    ->searchable()
                    ->sortable(),
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
