<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeePeriodicSalaryIncreaseResource\Pages;
use App\Filament\Resources\EmployeePeriodicSalaryIncreaseResource\RelationManagers;
use App\Models\EmployeePeriodicSalaryIncrease;
use App\Models\Employees;
use App\Models\MasterEmployeeBasicSalary;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;

class EmployeePeriodicSalaryIncreaseResource extends Resource
{
    protected static ?string $model = EmployeePeriodicSalaryIncrease::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Kenaikan Berkala';
    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Kenaikan Berkala Pegawai')
                    ->description('Form input data kenaikan berkala pegawai')
                    ->schema([
                        Forms\Components\TextInput::make('number_psi')
                            ->label('Nomor Surat Kenaikan Berkala')
                            ->maxLength(255)
                            ->required()
                            ->unique()
                            ->validationMessages([
                                'unique' => 'Nomor surat sudah ada. Perhatikan lagi nomor surat yang diinput.'
                            ]),

                        Forms\Components\DatePicker::make('date_periodic_salary_increase')
                            ->label('Tanggal Berkala')
                            ->required(),

                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'name')
                            ->label('Nama Pegawai')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state) {
                                    $employee = Employees::with('basicSalary.employeeGrade', 'basicSalary.serviceGrade')
                                        ->find($state);

                                    if ($employee && $employee->basicSalary) {
                                        // Set data dari basic salary yang ada di employees
                                        $set('employee_grade', "{$employee->basicSalary->employeeGrade->name}");
                                        $set('service_grade', "{$employee->basicSalary->serviceGrade->service_grade}");
                                        $set('amount', $employee->basicSalary->amount);
                                        $set('old_basic_salary_id', $employee->basic_salary_id); // Menyimpan old_basic_salary_id
                                    } else {
                                        $set('employee_grade', null);
                                        $set('service_grade', null);
                                        $set('amount', null);
                                        $set('old_basic_salary_id', null);
                                    }
                                }
                            }),

                        // Hidden field untuk menyimpan old_basic_salary_id
                        Forms\Components\Hidden::make('old_basic_salary_id'),

                        // Form untuk menampilkan data (disabled/readonly)
                        Forms\Components\TextInput::make('employee_grade')
                            ->label('Golongan')
                            ->disabled(),

                        Forms\Components\TextInput::make('service_grade')
                            ->label('Masa Kerja Golongan (MKG)')
                            ->disabled(),

                        Forms\Components\TextInput::make('amount')
                            ->label('Gaji Pokok Saat Ini')
                            ->prefix('Rp. ')
                            ->disabled()
                            ->numeric()
                            ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),

                        Forms\Components\Select::make('new_basic_salary_id')
                            ->label('Pilih Golongan dan Gaji Pokok Baru')
                            ->options(function () {
                                return MasterEmployeeBasicSalary::query()
                                    ->with(['employeeGrade', 'serviceGrade'])
                                    ->join('master_employee_grade', 'master_employee_basic_salary.employee_grade_id', '=', 'master_employee_grade.id')
                                    ->join('master_employee_service_grade', 'master_employee_basic_salary.employee_service_grade_id', '=', 'master_employee_service_grade.id')
                                    ->orderBy('master_employee_grade.name')
                                    ->orderBy('master_employee_service_grade.service_grade')
                                    ->select('master_employee_basic_salary.*')
                                    ->get()
                                    ->mapWithKeys(function ($basicSalary) {
                                        return [
                                            $basicSalary->id => "Golongan: {$basicSalary->employeeGrade->name} | MKG: {$basicSalary->serviceGrade->service_grade} - Gaji Pokok: Rp " . number_format($basicSalary->amount, 0, ',', '.')
                                        ];
                                    });
                            })
                            ->reactive()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->exists('master_employee_basic_salary', 'id')
                            ->validationMessages([
                                'required' => 'Golongan dan Gaji Pokok Baru wajib dipilih',
                                'exists' => 'Golongan dan Gaji Pokok Baru tidak valid'
                            ])
                            ->afterStateUpdated(function ($state, Forms\Set $set, callable $get) {
                                if ($state) {
                                    $basicSalary = MasterEmployeeBasicSalary::with(['employeeGrade', 'serviceGrade'])->find($state);
                                    if ($basicSalary) {
                                        $set('new_employee_grade', "{$basicSalary->employeeGrade->name}");
                                        $set('new_service_grade', "{$basicSalary->serviceGrade->service_grade}");
                                        $set('new_amount', $basicSalary->amount);

                                        // Ambil nilai amount saat ini
                                        $currentAmount = $get('amount');
                                        if ($currentAmount) {
                                            // Konversi keduanya ke integer untuk perhitungan yang akurat
                                            $currentAmountInt = (int)$currentAmount;  // amount sudah dalam bentuk angka dari database
                                            $newAmountInt = (int)$basicSalary->amount;

                                            // Hitung selisih
                                            $totalIncrease = $newAmountInt - $currentAmountInt;

                                            // Set nilai total
                                            $set('total_amount', $totalIncrease);
                                        }
                                    }
                                } else {
                                    $set('new_employee_grade', null);
                                    $set('new_service_grade', null);
                                    $set('new_amount', null);
                                    $set('total_amount', null);
                                }
                            }),

                        // Form untuk menampilkan data baru (disabled/readonly)
                        Forms\Components\TextInput::make('new_employee_grade')
                            ->label('Golongan Baru')
                            ->disabled(),

                        Forms\Components\TextInput::make('new_service_grade')
                            ->label('MKG Baru')
                            ->disabled(),

                        Forms\Components\TextInput::make('new_amount')
                            ->label('Gaji Pokok Baru')
                            ->prefix('Rp. ')
                            ->disabled()
                            ->numeric()
                            ->formatStateUsing(fn($state) => $state ? number_format($state, 0, ',', '.') : null),

                        // Dan untuk format tampilan totalnya
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Kenaikan Gaji')
                            ->prefix('Rp. ')
                            ->disabled()
                            ->numeric()
                            ->formatStateUsing(function ($state) {
                                if (!$state) return null;
                                // Format langsung ke format Rupiah
                                return number_format((int)$state, 0, '', '.');
                            }),

                        Forms\Components\FileUpload::make('docs_letter')
                            ->label('Lampiran Surat')
                            ->required()
                            ->rules([
                                'required',
                                'mimes:pdf',
                                'max:5024'
                            ])
                            ->validationMessages([
                                'max' => 'File anda terlalu besar. Lakukan kompres file sebelum melakukan input data.',
                                'mimes' => 'Hanya file PDF yang diperbolehkan',
                                'required' => 'Lampiran surat wajib diunggah'
                            ])
                            ->helperText('Hanya file dengan format .pdf yang diperbolehkan. Maksimal ukuran file 5MB'),

                        Forms\Components\FileUpload::make('docs_archive')
                            ->label('Lampiran Dokumen Pendukung')
                            ->rules('mimes:pdf|max:5024')
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
                Tables\Actions\BulkAction::make('Export Pdf')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        // Ambil data karyawan yang memiliki jabatan 'Kepala Sub Bagian Kepegawaian'
                        $employees = Employees::whereHas('employeePosition', function ($query) {
                            $query->where('name', 'Kepala Sub Bagian Kepegawaian');
                        })->first();

                        // Cek apakah pegawai ditemukan
                        if (!$employees) {
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
                        return response()->streamDownload(function () use ($records, $employees) {
                            $pdfContent = Blade::render('pdf.report_employee_periodic_salary_increase', [
                                'records' => $records,
                                'employees' => $employees
                            ]);
                            echo Pdf::loadHTML($pdfContent)->stream();
                        }, 'report_periodic_salary_increase.pdf');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('number_psi')
                    ->label('Nomor Surat')
                    ->searchable(),

                Tables\Columns\TextColumn::make('date_periodic_salary_increase')
                    ->label('Tanggal Berkala')
                    ->date()
                    ->formatStateUsing(fn(string $state): string => date('d F Y', strtotime($state)))
                    ->sortable(),

                Tables\Columns\TextColumn::make('employeePeriodic.name')
                    ->label('Nama Pegawai')
                    ->searchable(),

                // Data dari old_basic_salary_id
                Tables\Columns\TextColumn::make('oldBasicSalary.employeeGrade.name')
                    ->label('Golongan'),

                Tables\Columns\TextColumn::make('oldBasicSalary.serviceGrade.service_grade')
                    ->label('MKG'),

                Tables\Columns\TextColumn::make('oldBasicSalary.amount')
                    ->label('Gaji Pokok Saat Ini')
                    ->money('IDR'),

                // Data dari new_basic_salary_id
                Tables\Columns\TextColumn::make('newBasicSalary.employeeGrade.name')
                    ->label('Golongan Baru'),

                Tables\Columns\TextColumn::make('newBasicSalary.serviceGrade.service_grade')
                    ->label('MKG Baru'),

                Tables\Columns\TextColumn::make('newBasicSalary.amount')
                    ->label('Gaji Pokok Baru')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Kenaikan')
                    ->money('IDR')
                    ->state(function ($record): float {
                        $newAmount = $record->newBasicSalary->amount ?? 0;
                        $oldAmount = $record->oldBasicSalary->amount ?? 0;
                        return $newAmount - $oldAmount;
                    }),

                Tables\Columns\TextColumn::make('docs_letter')
                    ->label('Dokumen Surat')
                    ->searchable(),

                Tables\Columns\TextColumn::make('docs_archive')
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
            ])
            ->filters([
                SelectFilter::make('Nama Pegawai')
                    ->relationship('employeePeriodic', 'name'),
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
            'index' => Pages\ListEmployeePeriodicSalaryIncreases::route('/'),
            'create' => Pages\CreateEmployeePeriodicSalaryIncrease::route('/create'),
            'edit' => Pages\EditEmployeePeriodicSalaryIncrease::route('/{record}/edit'),
        ];
    }
}
