<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeePeriodicSalaryIncreaseResource\Pages;
use App\Filament\Resources\EmployeePeriodicSalaryIncreaseResource\RelationManagers;
use App\Models\EmployeePeriodicSalaryIncrease;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
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

    // Jika menggunakan Resource, tambahkan ini:
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $basic_salary = (float) ($data['basic_salary'] ?? 0);
        $salary_increase = (float) ($data['salary_increase'] ?? 0);
        $data['total_basic_salary'] = $basic_salary + $salary_increase;
        return $data;
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        $basic_salary = (float) ($data['basic_salary'] ?? 0);
        $salary_increase = (float) ($data['salary_increase'] ?? 0);
        $data['total_basic_salary'] = $basic_salary + $salary_increase;
        return $data;
    }


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
                            ->required(), // Menambahkan validasi required
                        Forms\Components\DatePicker::make('date_periodic_salary_increase')
                            ->label('Tanggal Berkala')
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->options(Employees::query()->pluck('name', 'id'))
                            ->afterStateUpdated(function ($set, $state) {
                                $employees = Employees::find($state);
                                if ($employees) {
                                    $set('name', $employees->name);
                                    $set('basic_salary', $employees->basic_salary);
                                } else {
                                    $set('name', null);
                                    $set('basic_salary', null);
                                }
                            })
                            ->label('Nama Pegawai')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->rules(['exists:employees,id']), // Validasi memastikan pegawai ada
                        Forms\Components\Hidden::make('employee_id')
                            ->label('Nama Pegawai')
                            ->required(),
                        Forms\Components\TextInput::make('basic_salary')
                            ->label('Gaji Pokok Awal')
                            ->required()
                            ->prefix('Rp. ')
                            ->numeric()
                            ->readonly()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $basic_salary = (float) $state;
                                $salary_increase = (float) $get('salary_increase') ?? 0;
                                $total = $basic_salary + $salary_increase;
                                $set('total_basic_salary', $total);
                            })
                            ->rules(['numeric', 'gt:0']),

                        Forms\Components\TextInput::make('salary_increase')
                            ->label('Kenaikan Gaji Pokok')
                            ->required()
                            ->prefix('Rp. ')
                            ->numeric()
                            ->rules(['numeric', 'gt:0'])
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $basic_salary = (float) $get('basic_salary') ?? 0;
                                $salary_increase = (float) $state;
                                $total = $basic_salary + $salary_increase;
                                $set('total_basic_salary', $total);
                            }),

                        Forms\Components\TextInput::make('total_basic_salary')
                            ->label('Total Gaji Pokok')
                            ->required()
                            ->prefix('Rp. ')
                            ->numeric()
                            ->disabled()
                            ->rules(['numeric', 'gt:0']),

                        Forms\Components\Hidden::make('total_basic_salary')
                            ->required(),
                        Forms\Components\FileUpload::make('docs_letter')
                            ->label('Lampiran Surat')
                            ->required()
                            ->rules('required|mimes:pdf|max:5024')
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('employeePeriodic.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salary_increase')
                    ->label('Kenaikan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_basic_salary')
                    ->label('Total Gaji Pokok')
                    ->numeric()
                    ->sortable(),
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
