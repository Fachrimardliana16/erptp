<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeJobApplicationArchivesResource\Pages;
use App\Filament\Resources\EmployeeJobApplicationArchivesResource\RelationManagers;
use App\Models\EmployeeJobApplicationArchives;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Exception;
use Illuminate\Database\QueryException;

class EmployeeJobApplicationArchivesResource extends Resource
{
    protected static ?string $model = EmployeeJobApplicationArchives::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Arsip Lamaran';
    protected static ?int $navigationSort = 1;

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
                            ->maxLength(255)
                            ->validationAttribute('Nomor Registrasi')
                            ->unique('employee_job_application_archives', 'registration_number', ignoreRecord: true)
                            ->rules('required|string|max:255')
                            ->validationMessages([
                                'required' => 'Nomor Registrasi wajib diisi',
                                'string' => 'Nomor Registrasi harus berupa teks',
                                'max' => 'Nomor Registrasi tidak boleh lebih dari :max karakter',
                                'unique' => 'Nomor Registrasi :input sudah terdaftar dalam sistem'
                            ]),

                        Forms\Components\DatePicker::make('registration_date')
                            ->label('Tanggal Registrasi Arsip')
                            ->required()
                            ->validationAttribute('Tanggal Registrasi Arsip')
                            ->rules('required|date')
                            ->validationMessages([
                                'required' => 'Tanggal Registrasi Arsip wajib diisi',
                                'date' => 'Format Tanggal Registrasi Arsip tidak valid',
                            ]),

                        Section::make('Profil Pelamar Kerja')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Pelamar')
                                    ->required()
                                    ->maxLength(255)
                                    ->validationAttribute('Nama Pelamar')
                                    ->rules('required|string|max:255')
                                    ->validationMessages([
                                        'required' => 'Nama Pelamar wajib diisi',
                                        'string' => 'Nama Pelamar harus berupa teks',
                                        'max' => 'Nama Pelamar tidak boleh lebih dari :max karakter',
                                    ]),

                                Forms\Components\TextInput::make('address')
                                    ->label('Alamat')
                                    ->required()
                                    ->maxLength(255)
                                    ->validationAttribute('Alamat')
                                    ->rules('required|string|max:255')
                                    ->validationMessages([
                                        'required' => 'Alamat wajib diisi',
                                        'string' => 'Alamat harus berupa teks',
                                        'max' => 'Alamat tidak boleh lebih dari :max karakter',
                                    ]),

                                Forms\Components\Select::make('gender')
                                    ->options([
                                        'laki-laki' => 'Laki-Laki',
                                        'perempuan' => 'Perempuan'
                                    ])
                                    ->label('Jenis Kelamin')
                                    ->required()
                                    ->validationAttribute('Jenis Kelamin')
                                    ->rules('required')
                                    ->validationMessages([
                                        'required' => 'Jenis Kelamin wajib dipilih',
                                    ]),

                                Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('place_of_birth')
                                            ->label('Tempat Lahir')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(1)
                                            ->validationAttribute('Tempat Lahir')
                                            ->rules('required|string|max:255')
                                            ->validationMessages([
                                                'required' => 'Tempat Lahir wajib diisi',
                                                'string' => 'Tempat Lahir harus berupa teks',
                                                'max' => 'Tempat Lahir tidak boleh lebih dari :max karakter',
                                            ]),

                                        Forms\Components\DatePicker::make('date_of_birth')
                                            ->label('Tanggal Lahir')
                                            ->required()
                                            ->columnSpan(1)
                                            ->validationAttribute('Tanggal Lahir')
                                            ->rules('required|date')
                                            ->validationMessages([
                                                'required' => 'Tanggal Lahir wajib diisi',
                                                'date' => 'Format Tanggal Lahir tidak valid',
                                            ]),
                                    ])
                                    ->columns(2),

                                Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('email')
                                            ->label('E-Mail')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->validationAttribute('e-Mail')
                                            ->unique('employee_job_application_archives', 'email', ignoreRecord: true) // Tambahan rule unique
                                            ->rules('required|email|max:255')
                                            ->validationMessages([
                                                'required' => 'E-Mail wajib diisi',
                                                'email' => 'Format E-Mail tidak valid',
                                                'max' => 'E-Mail tidak boleh lebih dari :max karakter',
                                                'unique' => 'Email :input sudah terdaftar dalam sistem' // Pesan untuk unique validation
                                            ]),

                                        Forms\Components\TextInput::make('contact')
                                            ->label('Kontak')
                                            ->required()
                                            ->maxLength(255)
                                            ->validationAttribute('Kontak')
                                            ->unique('employee_job_application_archives', 'contact', ignoreRecord: true)
                                            ->rules('required|string|max:255')
                                            ->validationMessages([
                                                'required' => 'Kontak wajib diisi',
                                                'string' => 'Kontak harus berupa teks',
                                                'max' => 'Kontak tidak boleh lebih dari :max karakter',
                                                'unique' => 'Nomor Kontak :input sudah terdaftar dalam sistem'
                                            ]),
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
                                    ->label('Agama')
                                    ->required()
                                    ->validationAttribute('Agama')
                                    ->rules('required')
                                    ->validationMessages([
                                        'required' => 'Agama wajib dipilih',
                                    ]),

                                Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('employee_education_id')
                                            ->label('Pendidikan Terakhir')
                                            ->relationship('employeedu', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->validationAttribute('Pendidikan Terakhir')
                                            ->rules('required')
                                            ->validationMessages([
                                                'required' => 'Pendidikan Terakhir wajib dipilih',
                                            ]),

                                        Forms\Components\TextInput::make('major')
                                            ->label('Jurusan')
                                            ->maxLength(255)
                                            ->validationAttribute('Jurusan')
                                            ->rules('required|string|max:255')
                                            ->validationMessages([
                                                'required' => 'Jurusan wajib diisi',
                                                'string' => 'Jurusan harus berupa teks',
                                                'max' => 'Jurusan tidak boleh lebih dari :max karakter',
                                            ]),
                                    ])
                                    ->columns(2),
                            ]),

                        Section::make('Berkas dan Catatan')
                            ->schema([
                                Forms\Components\FileUpload::make('archive_file')
                                    ->directory('Employee_JobApplicationArchive')
                                    ->label('Berkas Lamaran')
                                    ->required()
                                    ->validationAttribute('Berkas Lamaran')
                                    ->rules('required|mimes:pdf|max:5024')
                                    ->validationMessages([
                                        'required' => 'Berkas Lamaran wajib diunggah',
                                        'mimes' => 'Berkas Lamaran harus berformat PDF',
                                        'max' => 'Ukuran Berkas Lamaran tidak boleh lebih dari 5MB',
                                    ])
                                    ->helperText('Hanya file dengan format .pdf yang diperbolehkan. Maksimal ukuran file 5MB.'),

                                Forms\Components\Textarea::make('notes')
                                    ->label('Catatan')
                                    ->columnSpanFull()
                                    ->validationAttribute('Catatan')
                                    ->rules('nullable|string|max:1000')
                                    ->validationMessages([
                                        'string' => 'Catatan harus berupa teks',
                                        'max' => 'Catatan tidak boleh lebih dari :max karakter',
                                    ]),

                                Forms\Components\Hidden::make('users_id')
                                    ->default(auth()->id())
                                    ->validationAttribute('ID Pengguna'),
                            ])
                    ])
            ]);
    }

    protected function onCreateFailed(Exception $exception): void
    {
        if ($exception instanceof QueryException && $exception->errorInfo[1] === 1062) {
            // Mendapatkan nama kolom yang duplikat dari pesan error
            $message = $exception->getMessage();
            $fieldName = '';

            if (str_contains($message, 'email_unique')) {
                $fieldName = 'Email';
            } elseif (str_contains($message, 'contact_unique')) {
                $fieldName = 'Nomor Kontak';
            } elseif (str_contains($message, 'registration_number_unique')) {
                $fieldName = 'Nomor Registrasi';
            }

            Notification::make()
                ->title('Data Gagal Disimpan')
                ->body("{$fieldName} sudah terdaftar dalam sistem")
                ->danger()
                ->send();
            return;
        }

        parent::onCreateFailed($exception);
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
                            $pdfContent = Blade::render('pdf.report_employee_job_application_archive', [
                                'records' => $records,
                                'employee' => $employee
                            ]);
                            echo Pdf::loadHTML($pdfContent)
                                ->setPaper('A4', 'landscape') // Set ukuran kertas dan orientasi
                                ->stream();
                        }, 'report_job_application.pdf');
                    }),
            ])
            ->columns([
                TextColumn::make('No.')
                    ->rowIndex(),
                IconColumn::make('application_status')
                    ->label('Status Lamaran')
                    ->boolean(),
                TextColumn::make('registration_number')
                    ->label('No. Registrasi')
                    ->searchable(),
                TextColumn::make('registration_date')
                    ->label('Tanggal Registrasi')
                    ->date()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('place_of_birth')
                    ->label('Tempat')
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
                TextColumn::make('contact')
                    ->label('Kontak')
                    ->searchable(),
                TextColumn::make('religion')
                    ->label('Agama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employeedu.name')
                    ->label('Pendidikan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('major')
                    ->label('Jurusan')
                    ->searchable(),
                TextColumn::make('archive_file')
                    ->label('Berkas Lamaran')
                    ->url(fn($record) => asset('storage/' . $record->archive_file)) // Assumes files are stored in the "storage" directory
                    ->openUrlInNewTab(), // Ensures the PDF opens in a new tab
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Add filters here if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
