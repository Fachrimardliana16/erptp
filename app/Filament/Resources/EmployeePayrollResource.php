<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeePayrollResource\Pages;
use App\Filament\Resources\EmployeePayrollResource\RelationManagers;
use App\Models\EmployeePayroll;
use App\Models\Employees;
use App\Models\EmployeeSalary;
use Barryvdh\DomPDF\PDF as DomPDF;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeePayrollResource extends Resource
{
    protected static ?string $model = EmployeePayroll::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Payroll';
    protected static ?int $navigationSort = 15;


    protected static function calculateTotalBruto($get)
    {
        return (float) ($get('basic_salary') ?? 0) + (float) ($get('benefits_1') ?? 0) +
            (float) ($get('benefits_2') ?? 0) + (float) ($get('benefits_3') ?? 0) +
            (float) ($get('benefits_4') ?? 0) + (float) ($get('benefits_5') ?? 0) +
            (float) ($get('benefits_6') ?? 0) + (float) ($get('benefits_7') ?? 0) +
            (float) ($get('benefits_8') ?? 0) + (float) ($get('rounding') ?? 0) +
            (float) ($get('incentive') ?? 0) + (float) ($get('backpay') ?? 0);
    }

    protected static function calculateTotalCut($get)
    {
        return (float) ($get('paycut_1') ?? 0) + (float) ($get('paycut_2') ?? 0) +
            (float) ($get('paycut_3') ?? 0) + (float) ($get('paycut_4') ?? 0) +
            (float) ($get('paycut_5') ?? 0) + (float) ($get('paycut_6') ?? 0) +
            (float) ($get('paycut_7') ?? 0) + (float) ($get('paycut_8') ?? 0) +
            (float) ($get('paycut_9') ?? 0) + (float) ($get('paycut_10') ?? 0);
    }

    protected static function calculateNetto($get)
    {
        $grossAmount = self::calculateTotalBruto($get);
        $cutAmount = self::calculateTotalCut($get);

        return $grossAmount - $cutAmount;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['gross_amount'] = self::calculateTotalBruto($data);
        $data['cut_amount'] = self::calculateTotalCut($data);
        $data['netto'] = self::calculateNetto($data);

        return $data;
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
                                                    $set('grade_id', $employee->employee_grade_id);
                                                    $set('position_id', $employee->employee_position_id);
                                                    $set('basic_salary', $employee->basic_salary);
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
                                        Forms\Components\TextInput::make('gross_amount')
                                            ->label('Total Bruto')
                                            ->prefix('Rp. ')
                                            ->numeric()
                                            ->reactive()
                                            ->disabled()
                                            ->debounce(300)
                                            ->readOnly()
                                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                                try {
                                                    $totalBruto = self::calculateTotalBruto($get);
                                                    $set('gross_amount', $totalBruto);
                                                    $totalNetto = self::calculateNetto($get);
                                                    $set('netto', $totalNetto);
                                                } catch (\Throwable $e) {
                                                    session()->flash('error', $e->getMessage());
                                                }
                                            }),
                                    ]),
                                Tabs\Tab::make('Potongan dan Netto')
                                    ->schema([
                                        Forms\Components\TextInput::make('absence_count')
                                            ->label('Jumlah Absen')
                                            ->numeric(),
                                        Grid::make(1)
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        Repeater::make('paycuts')
                                                            ->schema([
                                                                Forms\Components\TextInput::make('description')
                                                                    ->required()
                                                                    ->label('Deskripsi Potongan'),

                                                                Forms\Components\TextInput::make('amount')
                                                                    ->numeric()
                                                                    ->required()
                                                                    ->rules(['numeric', 'min:0'])
                                                                    ->label('Besaran Potong')
                                                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                                                        $totalCut = collect($get('paycuts'))->sum('amount');
                                                                        $set('cut_amount', $totalCut);

                                                                        $grossAmount = $get('gross_amount') ?? 0;
                                                                        $set('netto', $grossAmount - $totalCut);
                                                                    }),
                                                            ])
                                                            ->columns(2)
                                                            ->label('Daftar Paycut')
                                                            ->createItemButtonLabel('Tambah Potongan')
                                                            ->collapsible()
                                                            ->defaultItems(0),

                                                        Forms\Components\Actions::make([
                                                            Forms\Components\Actions\Action::make('calculate')
                                                                ->label('Hitung Total')
                                                                ->icon('heroicon-m-calculator')
                                                                ->action(function (Get $get, Set $set) {
                                                                    $totalCut = collect($get('paycuts'))->sum('amount');
                                                                    $set('cut_amount', $totalCut);

                                                                    $grossAmount = $get('gross_amount') ?? 0;
                                                                    $set('netto', $grossAmount - $totalCut);
                                                                })
                                                        ])
                                                            ->verticalAlignment('end'),
                                                    ])
                                            ]),
                                        Forms\Components\TextInput::make('cut_amount')
                                            ->disabled()
                                            ->label('Total Paycut')
                                            ->numeric(),
                                        Forms\Components\Hidden::make('cut_amount'),

                                        Forms\Components\TextInput::make('netto')
                                            ->label('Jumlah Potongan')
                                            ->prefix('Rp. ')
                                            ->reactive()
                                            ->debounce(300)
                                            ->readOnly(),
                                        Forms\Components\Textarea::make('desc')
                                            ->label('Keterangan Potongan')
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
                TextColumn::make('basic_salary')
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
                Tables\Columns\TextColumn::make('paycuts')
                    ->label('Potongan Absensi')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return collect($record->paycuts)
                            ->map(function ($paycut) {
                                $amount = 'Rp ' . number_format($paycut['amount'], 0, ',', '.');
                                return "{$paycut['description']}: {$amount}";
                            })
                            ->join(', ');
                    })
                    // Jika ingin menampilkan total di bawah detail
                    ->description(function ($record) {
                        $total = collect($record->paycuts)->sum('amount');
                        return 'Total: Rp ' . number_format($total, 0, ',', '.');
                    }),
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
                Tables\Columns\TextColumn::make('desc')
                    ->label('Keterangan Potongan'),
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
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    // Action untuk download pdf aset per record
                    Action::make('download_pdf')
                        ->label('Cetak Slip Gaji')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($record) {
                            $pdf = app(abstract: DomPDF::class);
                            $pdf->loadView('pdf.employee_payroll_slip', ['payroll' => $record]);

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'slip_gaji_' . $record->name . '.pdf');
                        }),
                ]),
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
