<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeSalaryResource\Pages;
use App\Models\EmployeeBenefit;
use App\Models\Employees;
use App\Models\EmployeeSalary;
use App\Models\MasterEmployeeBenefit;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EmployeeSalaryResource extends Resource
{
    protected static ?string $model = EmployeeSalary::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Master Gaji';
    protected static ?int $navigationSort = 14;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 0 ? 'success' : 'warning';
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return sprintf(
            '%s - %s',
            $record->employee?->name ?? 'Pegawai',
            $record->employee?->nippam ?? 'No NIPPAM'
        );
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Departemen' => $record->employee?->departments?->name,
            'Total Gaji' => 'Rp ' . number_format($record->total_salary, 0, ',', '.')
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'employee.name',
            'employee.nippam',
            'employee.departments.name'
        ];
    }

    protected static function calculateSalaryDetails($employeeBenefit): array
    {
        if (!$employeeBenefit || !$employeeBenefit->employee) {
            return [
                'basic_salary' => 0,
                'benefits' => 0,
                'total' => 0
            ];
        }

        $basicSalary = $employeeBenefit->employee->basicSalary?->amount ?? 0;
        $totalBenefits = $employeeBenefit->getTotalAmount();

        return [
            'basic_salary' => $basicSalary,
            'benefits' => $totalBenefits,
            'total' => $basicSalary + $totalBenefits
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Form Master Gaji Pegawai')
                ->description('Form input master gaji pegawai')
                ->schema([
                    Grid::make(['default' => 1])
                        ->schema([
                            Select::make('employee_id')
                                ->relationship(
                                    name: 'employee',
                                    titleAttribute: 'name'
                                )
                                ->label('Nama Pegawai')
                                ->required()
                                ->live()
                                ->searchable()
                                ->preload()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if (!$state) {
                                        $set('employee_benefit_id', null);
                                        $set('basic_salary', null);
                                        $set('amount', null);
                                        $set('total_salary', null);
                                        return;
                                    }

                                    $employeeBenefit = EmployeeBenefit::with(['employee.basicSalary'])
                                        ->where('employee_id', $state)
                                        ->latest()
                                        ->first();

                                    if ($employeeBenefit) {
                                        $set('employee_benefit_id', $employeeBenefit->id);

                                        $basicSalary = $employeeBenefit->employee?->basicSalary?->amount ?? 0;
                                        $benefitDetails = $employeeBenefit->getBenefitDetails();
                                        $totalBenefits = $benefitDetails->sum('amount');

                                        $set('basic_salary', $basicSalary);

                                        // Set nilai untuk setiap tunjangan
                                        foreach ($benefitDetails as $benefit) {
                                            $set("benefit_{$benefit['id']}", $benefit['amount']);
                                        }

                                        $set('amount', $totalBenefits);
                                        $set('total_salary', $basicSalary + $totalBenefits);
                                    }
                                }),

                            TextInput::make('basic_salary')
                                ->label('Gaji Pokok')
                                ->disabled()
                                ->prefix('Rp')
                                ->numeric()
                                ->default(0),

                            Hidden::make('employee_benefit_id'),

                            Grid::make(2)
                                ->schema(function (Get $get) {
                                    $benefitId = $get('employee_benefit_id');
                                    if (!$benefitId) return [];

                                    $employeeBenefit = EmployeeBenefit::with(['employee'])->find($benefitId);
                                    if (!$employeeBenefit) return [];

                                    $benefitDetails = $employeeBenefit->getBenefitDetails();

                                    return $benefitDetails->map(function ($benefit) {
                                        return TextInput::make("benefit_{$benefit['id']}")
                                            ->label($benefit['name'])
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->default($benefit['amount'])
                                            ->numeric();
                                    })->toArray();
                                }),

                            TextInput::make('amount')
                                ->label('Total Tunjangan')
                                ->disabled()
                                ->prefix('Rp')
                                ->numeric(),

                            TextInput::make('total_salary')
                                ->label('Total Gaji')
                                ->disabled()
                                ->prefix('Rp')
                                ->numeric(),

                            Hidden::make('users_id')
                                ->default(auth()->id()),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        // Ambil semua master benefits
        $masterBenefits = MasterEmployeeBenefit::all(['id', 'name']);

        // Definisikan kolom dasar
        $columns = [
            Tables\Columns\TextColumn::make('employee.name')
                ->label('Nama Pegawai')
                ->searchable()
                ->sortable()
                ->description(fn($record): string => $record->employee?->nippam ?? '-'),

            Tables\Columns\TextColumn::make('employee.basicSalary.amount')
                ->label('Gaji Pokok')
                ->money('idr')
                ->sortable(),
        ];

        // Tambahkan kolom untuk setiap jenis tunjangan
        foreach ($masterBenefits as $benefit) {
            $columns[] = Tables\Columns\TextColumn::make("benefit_{$benefit->id}")
                ->label($benefit->name)
                ->money('idr')
                ->state(function ($record) use ($benefit) {
                    $employeeBenefit = EmployeeBenefit::where('employee_id', $record->employee_id)
                        ->latest()
                        ->first();

                    if (!$employeeBenefit) return 0;

                    foreach ($employeeBenefit->benefits as $b) {
                        if ($b['benefit_id'] === $benefit->id) {
                            return (float)$b['amount'];
                        }
                    }
                    return 0;
                });
        }

        // Tambahkan kolom total
        $columns = array_merge($columns, [
            TextColumn::make('amount')
                ->label('Total Tunjangan')
                ->money('idr')
                ->sortable(),

            TextColumn::make('total_salary')
                ->label('Total Gaji')
                ->money('idr')
                ->sortable()
                ->color('success')
                ->weight('bold'),

            TextColumn::make('created_at')
                ->label('Tanggal Dibuat')
                ->dateTime('d M Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->label('Tanggal Update')
                ->dateTime('d M Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ]);

        return $table
            ->columns($columns)
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('employee')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Pegawai')
                    ->multiple(),

                Tables\Filters\Filter::make('salary_range')
                    ->form([
                        TextInput::make('salary_from')
                            ->label('Gaji Dari')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('salary_until')
                            ->label('Gaji Sampai')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['salary_from'],
                                fn(Builder $query, $amount): Builder => $query->where('total_salary', '>=', $amount),
                            )
                            ->when(
                                $data['salary_until'],
                                fn(Builder $query, $amount): Builder => $query->where('total_salary', '<=', $amount),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['salary_from'] ?? null) {
                            $indicators['salary_from'] = 'Gaji dari: Rp ' . number_format($data['salary_from'], 0, ',', '.');
                        }
                        if ($data['salary_until'] ?? null) {
                            $indicators['salary_until'] = 'Gaji sampai: Rp ' . number_format($data['salary_until'], 0, ',', '.');
                        }
                        return $indicators;
                    }),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                // ... rest of your filter configuration
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Apakah Anda yakin ingin menghapus data gaji ini?'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Apakah Anda yakin ingin menghapus data gaji yang dipilih?'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->emptyStateDescription('Belum ada data gaji pegawai.')
            ->striped()
            ->poll('30s');
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withComplete()
            ->latest();
    }
}
