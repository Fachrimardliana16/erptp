<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeAssignmentLettersResource\Pages;
use App\Filament\Resources\EmployeeAssignmentLettersResource\RelationManagers;
use App\Models\EmployeeAssignmentLetters;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;

use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;

class EmployeeAssignmentLettersResource extends Resource
{
    protected static ?string $model = EmployeeAssignmentLetters::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Surat Tugas';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Surat Tugas')
                    ->description('Input data surat tugas.')
                    ->schema([
                        Forms\Components\TextInput::make('registration_number')
                            ->label('Nomor Surat')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('assigning_employee_id')
                            ->options(Employees::query()->pluck('name', 'id'))
                            ->afterStateUpdated(function ($set, $state) {
                                $employees = Employees::find($state);
                                $set('employee_position_id', $employees->employee_position_id);
                            })
                            ->relationship('aassigningEmployee', 'name')
                            ->label('Pemberi Tugas')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\Select::make('employee_position_id')
                            ->relationship('positionAssign', 'name')
                            ->label('Jabatan')
                            ->required(),
                        Forms\Components\Select::make('assigned_employees')
                            ->multiple()
                            ->relationship('assignedEmployees', 'name')
                            ->label('Penerima Tugas')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('task')
                            ->label('Detail Tugas')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Group::make([
                            Forms\Components\DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->required(),
                            Forms\Components\DatePicker::make('end_date')
                                ->label('Tanggal Selesai')
                                ->required(),
                        ])
                            ->columns(2),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Tambahan')
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
                Tables\Actions\BulkAction::make('Export Pdf') // Action untuk download PDF yang sudah difilter
                    ->icon('heroicon-m-arrow-down-tray')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        // Ambil data karyawan yang memiliki jabatan 'Kepala Sub Bagian Kepegawaian'
                        $employees = Employees::whereHas('employeePosition', function ($query) {
                            $query->where('name', 'Kepala Sub Bagian Kepegawaian');
                        })->first();

                        // Render PDF dengan data records dan employee
                        return response()->streamDownload(function () use ($records, $employees) {
                            $pdfContent = Blade::render('pdf.report_employee_assignmentletter', [
                                'records' => $records,
                                'employees' => $employees
                            ]);
                            echo Pdf::loadHTML($pdfContent)->stream();
                        }, 'report_assignment_letters.pdf');
                    }),
            ])

            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aassigningEmployee.name')
                    ->label('Pemberi Tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('positionAssign.name')
                    ->label('Jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignedEmployees.name')
                    ->label('Penerima Tugas')
                    ->listWithLineBreaks()
                    ->searchable(),
                Tables\Columns\TextColumn::make('task')
                    ->label('Tugas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
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
                Filter::make('Tanggal')
                    ->form([
                        DatePicker::make('Dari'),
                        DatePicker::make('Sampai'),
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Action untuk download pdf aset per record
                Action::make('download_pdf')
                    ->label('Cetak Surat Tugas')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        $pdf = app(abstract: DomPDF::class);
                        $pdf->loadView('pdf.employee_assignmentletter', ['surat_tugas' => $record]);

                        // Ambil semua nama pegawai yang menerima tugas
                        $namaPegawai = $record->assignedEmployees->pluck('name')->join('_'); // Menggabungkan nama pegawai dengan '_' sebagai pemisah
                        // Konversi string tanggal menjadi objek Carbon dan format
                        $tanggalMulai = Carbon::parse($record->start_date)->format('d-m-Y'); // Format tanggal mulai
                        $tanggalSelesai = Carbon::parse($record->end_date)->format('d-m-Y'); // Format tanggal selesai
                        // Format nama file
                        $fileName = "surat_tugas_{$tanggalMulai}_sd_{$tanggalSelesai}_{$namaPegawai}.pdf";

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
            'index' => Pages\ListEmployeeAssignmentLetters::route('/'),
            'create' => Pages\CreateEmployeeAssignmentLetters::route('/create'),
            'edit' => Pages\EditEmployeeAssignmentLetters::route('/{record}/edit'),
        ];
    }
}
