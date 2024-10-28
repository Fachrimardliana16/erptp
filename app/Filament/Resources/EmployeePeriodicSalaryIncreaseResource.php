<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeePeriodicSalaryIncreaseResource\Pages;
use App\Filament\Resources\EmployeePeriodicSalaryIncreaseResource\RelationManagers;
use App\Models\EmployeePeriodicSalaryIncrease;
use App\Models\Employees;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeePeriodicSalaryIncreaseResource extends Resource
{
    protected static ?string $model = EmployeePeriodicSalaryIncrease::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
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
                            ->required(), // Menambahkan validasi required
                        Forms\Components\DatePicker::make('date_periodic_salary_increase')
                            ->label('Tanggal Berkala')
                            ->required()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state > now()) {
                                    $set('date_periodic_salary_increase', null); // Reset jika tanggal lebih dari hari ini
                                    return 'Tanggal tidak boleh lebih dari hari ini';
                                }
                            }),
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
                            ->readonly(),
                        Forms\Components\TextInput::make('salary_increase')
                            ->label('Kenaikan Gaji Pokok')
                            ->required()
                            ->prefix('Rp. ')
                            ->numeric()
                            ->rules(['numeric', 'gt:0']), // Validasi untuk memastikan angka lebih besar dari 0
                        Forms\Components\FileUpload::make('docs_letter')
                            ->label('Lampiran Surat')
                            ->required(), // Menambahkan validasi required jika diperlukan
                        Forms\Components\FileUpload::make('docs_archive')
                            ->label('Lampiran Dokumen Pendukung'),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_psi')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_periodic_salary_increase')
                    ->label('')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee_id')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('basic_salary_id')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salary_increase')
                    ->label('')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('docs_letter')
                    ->label('')
                    ->searchable(),
                Tables\Columns\TextColumn::make('docs_archive')
                    ->label('')
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
            'index' => Pages\ListEmployeePeriodicSalaryIncreases::route('/'),
            'create' => Pages\CreateEmployeePeriodicSalaryIncrease::route('/create'),
            'edit' => Pages\EditEmployeePeriodicSalaryIncrease::route('/{record}/edit'),
        ];
    }
}
