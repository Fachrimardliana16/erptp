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
use Filament\Tables\Columns\ImageColumn;
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
                            ->validationAttribute('Nomor Perjanjian Kontrak')
                            ->rules(['string', 'max:255']),

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
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationAttribute('Status Kontrak'),

                        Select::make('departments_id')
                            ->relationship('agreementDepartement', 'name')
                            ->label('Bagian')
                            ->searchable()
                            ->preload()
                            ->live() // Make it live to trigger updates
                            ->afterStateUpdated(fn(callable $set) => $set('sub_department_id', null)) // Reset sub department when department changes
                            ->validationAttribute('Bagian'),

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
                            ->disabled(fn(callable $get) => ! $get('departments_id')) // Disable until department is selected
                            ->validationAttribute('Sub Bagian'),

                        Select::make('employee_position_id')
                            ->relationship('agreementPosition', 'name')
                            ->label('Jabatan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationAttribute('Jabatan'),

                        Select::make('employment_status_id')
                            ->relationship('agreementStatus', 'name')
                            ->label('Status Pegawai')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationAttribute('Status Pegawai'),

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

                        // TextInput untuk menampilkan grade (readonly)
                        TextInput::make('grade_id')
                            ->label('Golongan Pegawai')
                            ->validationAttribute('Golongan Pegawai')
                            ->disabled(),

                        // TextInput untuk menampilkan amount (readonly)
                        TextInput::make('amount')
                            ->label('Gaji Pokok')
                            ->validationAttribute('Gaji Pokok')
                            ->disabled(),
                        Group::make()
                            ->schema([
                                DatePicker::make('agreement_date_start')
                                    ->label('Tanggal Mulai Perjanjian')
                                    ->required()
                                    ->validationAttribute('Tanggal Mulai Perjanjian'),

                                DatePicker::make('agreement_date_end')
                                    ->label('Tanggal Akhir Perjanjian')
                                    ->required()
                                    ->validationAttribute('Tanggal Akhir Perjanjian'),
                            ])
                            ->columns(2),


                        FileUpload::make('docs')
                            ->directory('Perjanjian Kontrak')
                            ->label('Lampiran Dokumen')
                            ->required()
                            ->validationAttribute('Lampiran Dokumen')
                            ->rules('required|mimes:pdf|max:5024')
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
