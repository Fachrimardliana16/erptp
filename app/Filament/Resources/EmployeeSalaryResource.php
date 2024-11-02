<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeSalaryResource\Pages;
use App\Filament\Resources\EmployeeSalaryResource\RelationManagers;
use App\Models\EmployeeSalary;
use App\Models\Employees;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Fieldset;

class EmployeeSalaryResource extends Resource
{
    protected static ?string $model = EmployeeSalary::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Master Gaji';
    protected static ?int $navigationSort = 14;

    // Metode untuk menghitung total bruto
    protected static function calculateTotalBruto($get)
    {
        $values = [
            $get('basic_salary'),
            $get('benefits_1'),
            $get('benefits_2'),
            $get('benefits_3'),
            $get('benefits_4'),
            $get('benefits_5'),
            $get('benefits_6'),
            $get('benefits_7'),
            $get('benefits_8'),
        ];

        $total = 0;

        foreach ($values as $value) {
            // Pastikan nilai adalah angka atau string yang dapat dikonversi menjadi angka
            if (is_numeric($value)) {
                $total += intval($value);
            } else {
                // Anda bisa menambahkan logika penanganan kesalahan di sini
                // Misalnya, log kesalahan atau set nilai ke 0
                // session()->flash('error', 'Nilai tidak valid: ' . $value);
            }
        }

        return $total;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Master Gaji Pegawai')
                    ->description('Form input master gaji pegawai')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'name') // Mengambil relasi pegawai
                            ->label('Nama Pegawai')
                            ->required()
                            ->reactive() // Menjadikan field ini reaktif
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    // Mengambil data pegawai berdasarkan ID yang dipilih
                                    $employee = Employees::find($state);
                                    if ($employee) {
                                        // Mengupdate basic_salary dengan gaji pegawai yang dipilih
                                        $set('basic_salary', $employee->basic_salary);
                                    } else {
                                        // Jika pegawai tidak ditemukan, set basic_salary ke null
                                        $set('basic_salary', null);
                                    }
                                } else {
                                    // Jika tidak ada pegawai yang dipilih, set basic_salary ke null
                                    $set('basic_salary', null);
                                }
                            }),
                        Fieldset::make('Gaji dan Tunjangan')
                            ->schema([
                                TextInput::make('basic_salary')
                                    ->label('Gaji Pokok')
                                    ->prefix('Rp. ')
                                    ->numeric() // Pastikan hanya angka yang bisa dimasukkan
                                    ->reactive() // Menjadikan field ini reaktif
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        // Hitung total bruto berdasarkan perubahan di basic_salary
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    })
                                    ->disabled(),
                                TextInput::make('benefits_1')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan Keluarga')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->debounce(500) // Debounce selama 500 ms
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),

                                TextInput::make('benefits_2')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan Beras')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->debounce(500) // Debounce selama 500 ms
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),

                                TextInput::make('benefits_3')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan Jabatan')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->debounce(500) // Debounce selama 500 ms
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),

                                TextInput::make('benefits_4')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan Kesehatan')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->debounce(500) // Debounce selama 500 ms
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),

                                TextInput::make('benefits_5')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan Air')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->debounce(500) // Debounce selama 500 ms
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),

                                TextInput::make('benefits_6')
                                    ->prefix('Rp. ')
                                    ->label('Tunjangan DPLK')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->debounce(500) // Debounce selama 500 ms
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),

                                TextInput::make('benefits_7')
                                    ->prefix('Rp. ')
                                    ->label('Lain-lain')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->debounce(500) // Debounce selama 500 ms
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),

                                TextInput::make('benefits_8')
                                    ->prefix('Rp. ')
                                    ->label('Lain-lain')
                                    ->numeric()
                                    ->reactive()
                                    ->default('0')
                                    ->debounce(500) // Debounce selama 500 ms
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),

                                TextInput::make('amount')
                                    ->prefix('Rp. ')
                                    ->label('Total Bruto')
                                    ->disabled()
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $total = self::calculateTotalBruto($get);
                                        $set('amount', $total);
                                    }),
                            ]),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
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
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_1')
                    ->label('Tunjangan Keluarga')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_2')
                    ->label('Tunjangan beras')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_3')
                    ->label('Tunjangan Jabatan')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_4')
                    ->label('Tunjangan Kesehatan')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_5')
                    ->label('Tunjangan Air')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_6')
                    ->label('Tunjangan DPLK')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_7')
                    ->label('Lain-lain 1')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('benefits_8')
                    ->label('Lain-lain 2')
                    ->money('idr')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Total Bruto')
                    ->numeric()
                    ->money('idr')
                    ->sortable(),
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
            'index' => Pages\ListEmployeeSalaries::route('/'),
            'create' => Pages\CreateEmployeeSalary::route('/create'),
            'edit' => Pages\EditEmployeeSalary::route('/{record}/edit'),
        ];
    }
}
