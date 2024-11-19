<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeePromotionResource\Pages;
use App\Filament\Resources\EmployeePromotionResource\RelationManagers;
use App\Models\EmployeePromotion;
use App\Models\Employees;
use App\Models\MasterEmployeeBasicSalary;
use App\Models\MasterEmployeeGrade;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;

class EmployeePromotionResource extends Resource
{
    protected static ?string $model = EmployeePromotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Kenaikan Golongan';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Kenaikan Golongan Pegawai')
                    ->description('Form input kenaikan golongan pegawai')
                    ->schema([
                        Forms\Components\TextInput::make('decision_letter_number')
                            ->label('Nomor Surat Keputusan')
                            ->required(),
                        Forms\Components\DatePicker::make('promotion_date')
                            ->label('Tanggal Kenaikan Golongan')
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'name') // Menggunakan relasi untuk mendapatkan nama pegawai
                            ->label('Nama Pegawai')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->afterStateUpdated(function ($set, $state) {
                                if ($state) {
                                    // Mengambil data pegawai beserta basic salary dan grade
                                    $employee = Employees::with('employeeBasic.employeeGrade')->find($state);

                                    if ($employee && $employee->employeeBasic) {
                                        // Set data dari basic salary yang ada di employees
                                        $set('old_basic_salary_id', $employee->employeeBasic->id);
                                        $set('old_basic_salary', $employee->employeeBasic->amount); // Mengambil gaji pokok
                                        $set('employee_grade', $employee->employeeBasic->employeeGrade->name); // Menyimpan nama golongan
                                    } else {
                                        // Jika tidak ada data, set null
                                        $set('old_basic_salary_id', null);
                                        $set('old_basic_salary', null);
                                        $set('employee_grade', null);
                                    }
                                }
                            }),

                        // Hidden field untuk menyimpan old_basic_salary_id
                        Forms\Components\Hidden::make('old_basic_salary_id'), // Jika Anda ingin menyimpan ID gaji pokok

                        // Form untuk menampilkan data (disabled/readonly)
                        Forms\Components\TextInput::make('employee_grade')
                            ->label('Golongan')
                            ->disabled(), // Menonaktifkan field agar tidak bisa diedit

                        Forms\Components\TextInput::make('old_basic_salary')
                            ->label('Gaji Pokok Saat Ini')
                            ->prefix('Rp. ')
                            ->disabled() // Menonaktifkan field agar tidak bisa diedit
                            ->numeric()
                            ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')), // Format untuk menampilkan uang
                        Forms\Components\Select::make('new_basic_salary_id')
                            ->label('Golongan Baru')
                            ->options(function () {
                                return MasterEmployeeGrade::query()
                                    ->whereIn('master_employee_grade.name', ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'B3', 'B4', 'C1', 'C2', 'C3', 'C4'])
                                    ->leftJoin('master_employee_basic_salary', 'master_employee_grade.id', '=', 'master_employee_basic_salary.employee_grade_id')
                                    ->leftJoin('master_employee_service_grade', 'master_employee_basic_salary.employee_service_grade_id', '=', 'master_employee_service_grade.id')
                                    ->where(function ($query) {
                                        $query->where('master_employee_service_grade.service_grade', '0')
                                            ->orWhere('master_employee_service_grade.service_grade', '3');
                                    })
                                    ->selectRaw("CONCAT('Golongan: ', master_employee_grade.name, ' | MKG: ', master_employee_service_grade.service_grade, ' | Gaji Pokok: Rp. ', COALESCE(FORMAT(master_employee_basic_salary.amount, 0), 'N/A')) AS grade_amount, master_employee_basic_salary.id AS basic_salary_id, master_employee_basic_salary.amount AS basic_salary_amount, master_employee_grade.name AS grade_name")
                                    ->orderBy('grade_name')
                                    ->orderBy('master_employee_service_grade.service_grade')
                                    ->pluck('grade_amount', 'basic_salary_id');
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $basicSalary = MasterEmployeeBasicSalary::find($state);
                                    $set('new_basic_salary_id', $basicSalary?->id);
                                    $set('new_basic_salary', $basicSalary?->amount);
                                } else {
                                    $set(
                                        'new_basic_salary_id',
                                        null
                                    );
                                    $set('new_basic_salary', null);
                                }
                            }),
                        Forms\Components\hidden::make('new_basic_salary_id'),
                        Forms\Components\TextInput::make('new_basic_salary')
                            ->label('Gaji Pokok')
                            ->prefix('Rp. ')
                            ->disabled() // Menonaktifkan field agar tidak bisa diedit
                            ->numeric()
                            ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')), // Format untuk menampilkan uang
                        Forms\Components\FileUpload::make('doc_promotion')
                            ->directory('Employee_Promotion')
                            ->label('Berkas Kenaikan Golongan')
                            ->required()
                            ->validationAttribute('Berkas Kenaikan Golongan')
                            ->rules('required|mimes:pdf|max:5024')
                            ->helperText('Hanya file dengan format .pdf yang diperbolehkan. Maksimal ukuran file 5MB'),
                        Forms\Components\TextArea::make('desc')
                            ->label('Catatan Kenaikan Golongan'),
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
                            Notification::make()
                                ->title('Kesalahan')
                                ->danger()
                                ->body('Tidak ada pegawai dengan jabatan Kepala Sub Bagian Kepegawaian.')
                                ->persistent()
                                ->send();
                            return;
                        }

                        // Render PDF dengan data records dan employee
                        return response()->streamDownload(function () use ($records, $employees) {
                            $pdfContent = Blade::render('pdf.report_employee_promotion', [
                                'records' => $records,
                                'employees' => $employees
                            ]);
                            echo Pdf::loadHTML($pdfContent)->stream();
                        }, 'report_employee_promotion.pdf');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('decision_letter_number')
                    ->label('Nomor SK')
                    ->sortable(),
                Tables\Columns\TextColumn::make('promotion_date')
                    ->label('Tanggal Mutasi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Nama Pegawai')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('oldGrade.name')
                    ->label('Golongan Lama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('oldBasicSalary.amount')
                    ->label('Gaji Pokok Lama')
                    ->sortable()
                    ->money('Rp. ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('newGrade.name')
                    ->label('Golongan Baru')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('newBasicSalary.amount')
                    ->label('Gaji Pokok Baru')
                    ->money('Rp. ')
                    ->sortable()
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
            'index' => Pages\ListEmployeePromotions::route('/'),
            'create' => Pages\CreateEmployeePromotion::route('/create'),
            'edit' => Pages\EditEmployeePromotion::route('/{record}/edit'),
        ];
    }
}
