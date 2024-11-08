<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeBusinessTravelLettersResource\Pages;
use App\Filament\Resources\EmployeeBusinessTravelLettersResource\RelationManagers;
use App\Models\EmployeeBusinessTravelLetters;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\RegistrationNumberController;

class EmployeeBusinessTravelLettersResource extends Resource
{
    protected static ?string $model = EmployeeBusinessTravelLetters::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Surat Perjalanan Dinas';
    protected static ?int $navigationSort = 9;

    private $registrationNumberController;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->registrationNumberController = new RegistrationNumberController();
    }

    public function toArray($request)
    {
        return [
            'registration_number' => $this->registrationNumberController->generateRegistrationNumber(),
            // ... other fields
        ];
    }
    protected static function afterSave($record, $data)
    {
        // Mengaitkan followers setelah penyimpanan model
        if (isset($data['followers'])) {
            $record->followers()->sync($data['followers']);
        }
    }

    public static function form(Form $form): Form
    {

        $registrationNumberController = new RegistrationNumberController();
        return $form
            ->schema([
                Section::make('Form Input Surat Perjalanan Dinas')
                    ->description('Input data perjalanan dinas.')
                    ->schema([
                        Forms\Components\TextInput::make('registration_number')
                            ->label('Nomor Surat')
                            ->required()
                            ->maxLength(255)
                            ->default($registrationNumberController->generateRegistrationNumber()),
                        Group::make()
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->required(),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Tanggal Selesai')
                            ])
                            ->columns(2),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('businessTravelEmployee', 'name')
                            ->label('Nama Pegawai')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('followers')
                            ->label('Pegawai Pengikut')
                            ->multiple()
                            ->relationship('followers', 'name')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->minItems(1),
                        Forms\Components\TextInput::make('destination')
                            ->label('Tujuan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('destination_detail')
                            ->label('Detail Tujuan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('purpose_of_trip')
                            ->label('Maksud Dinas')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_trip_expenses')
                            ->label('Pembiayaan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('pasal')
                            ->label('Pasal')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('employee_signatory_id')
                            ->relationship('employeeSignatory', 'name')
                            ->label('Pejabat Penandatangan')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return Employees::whereHas('employeePosition', function ($query) {
                                    $query->where('name', 'like', '%direktur utama%')
                                        ->orWhere('name', 'like', '%direktur umum%')
                                        ->orWhere('name', 'like', '%kepala bagian umum%');
                                })->get()->mapWithKeys(function ($employee) {
                                    return [
                                        $employee->id => $employee->name . ' | ' . $employee->employeePosition->name
                                    ];
                                });
                            }),
                        Forms\Components\Textarea::make('description')
                            ->label('Detail Tugas')
                            ->columnSpanFull(),
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
                        $pdfContent = Blade::render('pdf.report_employee_business_travel', [
                            'records' => $records,
                            'employees' => $employees
                        ]);
                        echo Pdf::loadHTML($pdfContent)
                        ->setPaper('A4', 'landscape') // Set ukuran kertas dan orientasi
                        ->stream();
                    }, 'report_business_travel.pdf');
                }),
        ])
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('Nomor Surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('businessTravelEmployee.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('followers.name')
                    ->label('Pegawai Pengikut')
                    ->listWithLineBreaks()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('day_count')
                    ->label('Lama Perjalanan')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->day_count . ' hari'),
                Tables\Columns\TextColumn::make('destination')
                    ->label('Tujuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('destination_detail')
                    ->label('Detail Tujuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purpose_of_trip')
                    ->label('Maksud Perjalanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_trip_expenses')
                    ->label('Pembiayaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pasal')
                    ->label('Pasal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employeeSignatory.name')
                    ->label('Pejabat Penandatangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Tugas')
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
                Action::make('download_pdf')
                    ->label('Cetak SPPD')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        $pdf = app(DomPDF::class);
                        $pdf->loadView('pdf.employee_business_travel_letter', ['surat_tugas' => $record]);

                        // Format nama file
                        $namaPegawai = $record->businessTravelEmployee->name;
                        $tanggalMulai = \Carbon\Carbon::parse($record->start_date)->format('d-m-Y');
                        $tanggalSelesai = \Carbon\Carbon::parse($record->end_date)->format('d-m-Y');
                        $fileName = "SPPD_{$namaPegawai}_{$tanggalMulai}_sd_{$tanggalSelesai}.pdf";

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, $fileName);
                    }),
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
            'index' => Pages\ListEmployeeBusinessTravelLetters::route('/'),
            'create' => Pages\CreateEmployeeBusinessTravelLetters::route('/create'),
            'edit' => Pages\EditEmployeeBusinessTravelLetters::route('/{record}/edit'),
        ];
    }
}
