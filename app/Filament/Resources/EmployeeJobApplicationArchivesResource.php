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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;

class EmployeeJobApplicationArchivesResource extends Resource
{
    protected static ?string $model = EmployeeJobApplicationArchives::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
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
                            ->validationAttribute('Nomor Registrasi'),
                        Forms\Components\DatePicker::make('registration_date')
                            ->label('Tanggal Registrasi Arsip')
                            ->required()
                            ->validationAttribute('Tanggal Registrasi Arsip'),
                    ]),
                Section::make('Profil Pelamar Kerja')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Pelamar')
                            ->required()
                            ->maxLength(255)
                            ->validationAttribute('Nama Pelamar'),
                        Forms\Components\TextInput::make('address')
                            ->label('Alamat')
                            ->required()
                            ->maxLength(255)
                            ->validationAttribute('Alamat'),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'laki-laki' => 'Laki-Laki',
                                'perempuan' => 'Perempuan'
                            ])
                            ->label('Jenis Kelamin')
                            ->required()
                            ->validationAttribute('Jenis Kelamin'),
                        Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('place_of_birth')
                                    ->label('Tempat Lahir')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(1)
                                    ->validationAttribute('Tempat Lahir'),
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->label('Tanggal Lahir')
                                    ->required()
                                    ->columnSpan(1)
                                    ->validationAttribute('Tanggal Lahir'),
                            ])
                            ->columns(2),
                        Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('E-Mail')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->validationAttribute('E-Mail')
                                    ->helperText('Pastikan format email benar'),
                                Forms\Components\TextInput::make('contact')
                                    ->label('Kontak')
                                    ->required()
                                    ->maxLength(255)
                                    ->validationAttribute('Kontak')
                                    ->helperText('Nomor telepon harus valid'),
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
                            ->validationAttribute('Agama'),
                        Group::make()
                            ->schema([
                                Forms\Components\Select::make('employee_education_id')
                                    ->label('Pendidikan Terakhir')
                                    ->relationship('employeedu', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->validationAttribute('Pendidikan Terakhir'),
                                Forms\Components\TextInput::make('major')
                                    ->label('Jurusan')
                                    ->maxLength(255)
                                    ->validationAttribute('Jurusan'),
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
                            ->helperText('Hanya file dengan format .pdf yang diperbolehkan.'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->validationAttribute('Catatan'),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id())
                            ->validationAttribute('ID Pengguna'),
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
