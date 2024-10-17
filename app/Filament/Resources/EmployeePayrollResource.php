<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeePayrollResource\Pages;
use App\Filament\Resources\EmployeePayrollResource\RelationManagers;
use App\Models\EmployeePayroll;
use App\Models\Employees;
use App\Models\EmployeeSalary;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeePayrollResource extends Resource
{
    protected static ?string $model = EmployeePayroll::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static function calculateTotalBruto($get)
    {
        return ($get('basic_salary') ?? 0) + ($get('benefits_1') ?? 0) + ($get('benefits_2') ?? 0) +
            ($get('benefits_3') ?? 0) + ($get('benefits_4') ?? 0) + ($get('benefits_5') ?? 0) +
            ($get('benefits_6') ?? 0) + ($get('benefits_7') ?? 0) + ($get('benefits_8') ?? 0) +
            ($get('rounding') ?? 0) + ($get('incentive') ?? 0) + ($get('backpay') ?? 0);
    }

    protected static function calculateTotalCut($get)
    {
        return ($get('paycut_1') ?? 0) + ($get('paycut_2') ?? 0) + ($get('paycut_3') ?? 0) +
            ($get('paycut_4') ?? 0) + ($get('paycut_5') ?? 0) + ($get('paycut_6') ?? 0) +
            ($get('paycut_7') ?? 0) + ($get('paycut_8') ?? 0) + ($get('paycut_9') ?? 0)
            + ($get('paycut_10') ?? 0);
    }

    protected static function calculateNetto($get)
    {
        $grossAmount = self::calculateTotalBruto($get);
        $cutAmount = self::calculateTotalCut($get);
        return $grossAmount - $cutAmount;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Penggajian Pegawai')
                    ->schema([
                        Forms\Components\Tabs::make('')
                            ->tabs([
                                Tabs\Tab::make('Profil Pegawai')
                                    ->schema([
                                        Forms\Components\TextInput::make('periode')
                                            ->label('Periode Gaji')
                                            ->placeholder('MMYYYY')
                                            ->required()
                                            ->helperText('Masukkan dalam format MMYYYY'),
                                        Forms\Components\Select::make('employee_id')
                                            ->options(Employees::query()->pluck('name', 'id'))
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $employee = Employees::find($state);
                                                if ($employee) {
                                                    $set('status_id', $employee->employment_status_id);
                                                    $set('grade_id', $employee->basicSalary->employee_grade_id);
                                                    $set('position_id', $employee->employee_position_id);
                                                }
                                            })
                                            ->label('Pegawai')
                                            ->required(),
                                        Forms\Components\Select::make('status_id')
                                            ->relationship('status', 'name')
                                            ->reactive()
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->label('Status')
                                            ->required()
                                            ->disabled(),
                                        Forms\Components\Hidden::make('status_id')
                                            ->reactive()
                                            ->label('Status')
                                            ->required(),
                                        Forms\Components\Select::make('grade_id')
                                            ->relationship('grade', 'name')
                                            ->reactive()
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->label('Golongan')
                                            ->required()
                                            ->disabled(),
                                        Forms\Components\Hidden::make('grade_id')
                                            ->reactive()
                                            ->label('Golongan')
                                            ->required(),
                                        Forms\Components\Select::make('position_id')
                                            ->relationship('position', 'name')
                                            ->reactive()
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->label('Jabatan')
                                            ->required()
                                            ->disabled(),
                                        Forms\Components\Hidden::make('position_id')
                                            ->reactive()
                                            ->label('Jabatan')
                                            ->required(),
                                        Forms\Components\Hidden::make('users_id')
                                            ->default(auth()->id()),
                                    ]),
                                Tabs\Tab::make('Tunjangan dan Bruto')
                                    ->schema([
                                        // Forms\Components\Select::make('employee_id')
                                        //     ->options(Employees::query()->pluck('name', 'id'))
                                        //     ->reactive()
                                        //     ->afterStateUpdated(function ($state, callable $set) {
                                        //         $employee = Employees::find($state);
                                        //         if ($employee) {
                                        //             $set('status_id', $employee->employment_status_id);
                                        //             $set('grade_id', $employee->basicSalary->employee_grade_id);
                                        //             $set('position_id', $employee->employee_position_id);
                                        //         }
                                        //     })
                                        //     ->label('Pegawai')
                                        //     ->required(),
                                        Forms\Components\Select::make('salary_id')
                                            ->options(EmployeeSalary::with('employee')->get()->pluck('employee.name', 'id'))
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $employee = EmployeeSalary::find($state);
                                                if ($employee) {
                                                    $set('basic_salary', $employee->basic_salary);
                                                    $set('benefits_1', $employee->benefits_1);
                                                    $set('benefits_2', $employee->benefits_2);
                                                    $set('benefits_3', $employee->benefits_3);
                                                    $set('benefits_4', $employee->benefits_4);
                                                    $set('benefits_5', $employee->benefits_5);
                                                    $set('benefits_6', $employee->benefits_6);
                                                    $set('benefits_7', $employee->benefits_7);
                                                    $set('benefits_8', $employee->benefits_12);
                                                }
                                            })
                                            ->label('Pegawai')
                                            ->required(),
                                        Forms\Components\TextInput::make('basic_salary')
                                            ->reactive('')
                                            ->label('Gaji Pokok')
                                            ->prefix('Rp. ')
                                            ->disabled()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\Hidden::make('basic_salary')
                                            ->reactive()
                                            ->label('Gaji Pokok')
                                            ->required(),
                                        Forms\Components\TextInput::make('benefits_1')
                                            ->reactive('')
                                            ->label('Tunjangan Keluarga')
                                            ->prefix('Rp. ')
                                            ->disabled()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\Hidden::make('benefits_1')
                                            ->reactive()
                                            ->label('Tunjangan Keluarga')
                                            ->required(),
                                        Forms\Components\TextInput::make('benefits_2')
                                            ->reactive('')
                                            ->label('Tunjungan Beras')
                                            ->prefix('Rp. ')
                                            ->disabled()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\Hidden::make('benefits_2')
                                            ->reactive()
                                            ->label('Tunjangan Beras')
                                            ->required(),
                                        Forms\Components\TextInput::make('benefits_3')
                                            ->reactive('')
                                            ->label('Tunjangan Jabatan')
                                            ->prefix('Rp. ')
                                            ->disabled()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\Hidden::make('benefits_3')
                                            ->reactive()
                                            ->label('Tunjangan Jabatan')
                                            ->required(),
                                        Forms\Components\TextInput::make('benefits_4')
                                            ->reactive('')
                                            ->label('Tunjangan Kesehatan')
                                            ->prefix('Rp. ')
                                            ->disabled()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\Hidden::make('benefits_4')
                                            ->reactive()
                                            ->label('Tunjangan Kesehatan')
                                            ->required(),
                                        Forms\Components\TextInput::make('benefits_5')
                                            ->reactive('')
                                            ->label('Tunjangan Air')
                                            ->prefix('Rp. ')
                                            ->disabled()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\Hidden::make('benefits_5')
                                            ->reactive()
                                            ->label('Tunjangan Air')
                                            ->required(),
                                        Forms\Components\TextInput::make('benefits_6')
                                            ->reactive('')
                                            ->label('Tunjangan DPLK')
                                            ->prefix('Rp. ')
                                            ->disabled()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\Hidden::make('benefits_6')
                                            ->reactive()
                                            ->label('Tunjangan DPLK')
                                            ->required(),
                                        Forms\Components\TextInput::make('benefits_7')
                                            ->reactive('')
                                            ->label('Lain-lain')
                                            ->prefix('Rp. ')
                                            ->disabled()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\Hidden::make('benefits_7')
                                            ->reactive()
                                            ->label('Lain-lain'),
                                        Forms\Components\TextInput::make('benefits_8')
                                            ->reactive('')
                                            ->label('Lain-lain')
                                            ->prefix('Rp. ')
                                            ->disabled()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\Hidden::make('benefits_8')
                                            ->reactive()
                                            ->label('Lain-lain'),
                                        Forms\Components\TextInput::make('rounding')
                                            ->prefix('Rp. ')
                                            ->label('Pembulatan')
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('incentive')
                                            ->prefix('Rp. ')
                                            ->label('Insentif')
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('backpay')
                                            ->prefix('Rp. ')
                                            ->label('Rapelan')
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('gross_amount')
                                            ->prefix('Rp. ')
                                            ->label('Total Bruto')
                                            ->numeric()
                                            ->disabled()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalBruto = self::calculateTotalBruto($get);
                                                $set('gross_amount', $totalBruto);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                    ]),
                                Tabs\Tab::make('Potongan dan Netto')
                                    ->schema([
                                        Forms\Components\TextInput::make('absence_count')
                                            ->label('Jumlah Absen')
                                            ->numeric(),
                                        // Forms\Components\TextInput::make('benefits_4')
                                        //     ->reactive('')
                                        //     ->label('Tunjangan Kesehatan')
                                        //     ->prefix('Rp. ')
                                        //     ->disabled()
                                        //     ->numeric()
                                        //     ->reactive()
                                        //     ->afterStateUpdated(function ($state, callable $set, $get) {
                                        //         $total = self::calculateTotalBruto($get);
                                        //         $set('gross_amount', $total);
                                        //     }),
                                        // Forms\Components\Hidden::make('benefits_4')
                                        //     ->reactive()
                                        //     ->label('Tunjangan Kesehatan')
                                        //     ->required(),
                                        Forms\Components\TextInput::make('paycut_1')
                                            ->label('Tabungan Daging')
                                            ->reactive()
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('paycut_2')
                                            ->label('Rokok')
                                            ->reactive()
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('paycut_3')
                                            ->label('Arisan Sumber')
                                            ->reactive()
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('paycut_4')
                                            ->label('Cendera Mata')
                                            ->reactive()
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('paycut_5')
                                            ->label('ASTEK')
                                            ->reactive()
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('paycut_6')
                                            ->label('BPJS')
                                            ->reactive()
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('paycut_7')
                                            ->label('Tab. Koperasi')
                                            ->reactive()
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('paycut_8')
                                            ->label('Dapenma')
                                            ->reactive()
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('paycut_9')
                                            ->label('DANSOS KOP')
                                            ->reactive()
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('paycut_10')
                                            ->label('Potongan Absen')
                                            ->reactive()
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            }),
                                        Forms\Components\TextInput::make('cut_amount')
                                            ->label('Jumlah Potongan')
                                            ->prefix('Rp. ')
                                            ->reactive()
                                            ->disabled()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            })
                                            ->numeric(),
                                        Forms\Components\TextInput::make('netto')
                                            ->label('Jumlah Netto')
                                            ->prefix('Rp. ')
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                $totalCut = self::calculateTotalCut($get);
                                                $set('cut_amount', $totalCut);
                                                $totalNetto = self::calculateNetto($get);
                                                $set('netto', $totalNetto);
                                            })
                                            ->reactive()
                                            ->disabled()
                                            ->numeric(),
                                        Forms\Components\Textarea::make('desc')
                                            ->label('')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('periode')
                    ->label('Periode')
                    ->date('M Y') // Mengatur format hanya bulan dan tahun
                    ->sortable(),
                TextColumn::make('employee.name')
                    ->label('Nama Pegawai')
                    ->searchable(),

                TextColumn::make('status.name')
                    ->label('Status')
                    ->searchable(),

                TextColumn::make('grade.name')
                    ->label('Golongan')
                    ->searchable(),

                TextColumn::make('position.name')
                    ->label('Jabatan')
                    ->searchable(),

                TextColumn::make('salary.amount')
                    ->Money('IDR')
                    ->label('Gaji Pokok')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_1')
                    ->Money('IDR')
                    ->label('Tunjangan Keluarga')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('benefits_2')
                    ->Money('IDR')
                    ->label('Tunjangan Beras')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('benefits_3')
                    ->Money('IDR')
                    ->label('Tunjangan Jabatan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('benefits_4')
                    ->Money('IDR')
                    ->label('Tunjangan Kesehatan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('benefits_5')
                    ->Money('IDR')
                    ->label('Tunjangan Air')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('benefits_6')
                    ->Money('IDR')
                    ->label('Tunjangan DPLK')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('benefits_7')
                    ->Money('IDR')
                    ->label('Lain-lain')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('benefits_8')
                    ->Money('IDR')
                    ->label('Lain-lain')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rounding')
                    ->Money('IDR')
                    ->label('Pembulatan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('incentive')
                    ->Money('IDR')
                    ->label('Insentif')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('backpay')
                    ->Money('IDR')
                    ->label('Rapel')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gross_amount')
                    ->Money('IDR')
                    ->label('Total Bruto')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('absence_count')
                    ->Money('IDR')
                    ->label('Absen')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paycut_1')
                    ->Money('IDR')
                    ->label('Tabungan Daging')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paycut_2')
                    ->Money('IDR')
                    ->label('Rokok')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paycut_3')
                    ->Money('IDR')
                    ->label('Arisan Sumber')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paycut_4')
                    ->Money('IDR')
                    ->label('Cendera Mata')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paycut_5')
                    ->Money('IDR')
                    ->label('ASTEK')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paycut_6')
                    ->Money('IDR')
                    ->label('BPJS')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paycut_7')
                    ->Money('IDR')
                    ->label('Tabungan Koperasi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paycut_8')
                    ->Money('IDR')
                    ->label('Dapenma')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paycut_9')
                    ->Money('IDR')
                    ->label('Dana Sosial Koperasi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paycut_10')
                    ->Money('IDR')
                    ->label('Potongan Absensi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cut_amount')
                    ->Money('IDR')
                    ->label('Jumlah Potongan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('netto')
                    ->Money('IDR')
                    ->label('Total Penerimaan')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListEmployeePayrolls::route('/'),
            'create' => Pages\CreateEmployeePayroll::route('/create'),
            'edit' => Pages\EditEmployeePayroll::route('/{record}/edit'),
        ];
    }
}
