<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeBenefitResource\Pages;
use App\Models\EmployeeBenefit;
use App\Models\Employees;
use App\Models\MasterEmployeeBenefit;
use App\Models\MasterEmployeeGradeBenefit;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EmployeeBenefitResource extends Resource
{
    protected static ?string $model = EmployeeBenefit::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Tunjangan Pegawai';

    protected static ?string $modelLabel = 'Tunjangan Pegawai';

    protected static ?string $pluralModelLabel = 'Tunjangan Pegawai';

    protected static ?string $navigationGroup = 'Employee';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Tunjangan Pegawai')
                    ->description('Kelola tunjangan pegawai berdasarkan golongan')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->relationship(
                                name: 'employee',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn($query) => $query->with(['grade', 'basicSalary'])
                            )
                            ->label('Nama Pegawai')
                            ->required()
                            ->live()
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (Set $set) {
                                $set('benefits', []);
                            })
                            ->validationMessages([
                                'required' => 'Silakan pilih pegawai terlebih dahulu',
                            ])
                            ->helperText('Pilih pegawai untuk melihat tunjangan yang tersedia'),

                        Forms\Components\Repeater::make('benefits')
                            ->schema([
                                Forms\Components\Select::make('benefit_id')
                                    ->label('Tunjangan')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->options(function (Get $get) {
                                        $employeeId = $get('../../employee_id');

                                        if (!$employeeId) {
                                            return [];
                                        }

                                        $employee = Employees::with(['grade', 'basicSalary'])
                                            ->find($employeeId);

                                        if (!$employee?->grade?->id) {
                                            return [];
                                        }

                                        $benefits = MasterEmployeeGradeBenefit::query()
                                            ->where('grade_id', $employee->grade->id)
                                            ->with(['gradeBenefits', 'benefit'])
                                            ->get();

                                        if ($benefits->isEmpty()) {
                                            return [];
                                        }

                                        return $benefits->mapWithKeys(function ($gradeBenefit) {
                                            $gradeName = $gradeBenefit->gradeBenefits?->name ?? 'N/A';
                                            $benefitName = $gradeBenefit->benefit?->name ?? 'N/A';
                                            $amount = number_format($gradeBenefit->amount, 0, ',', '.');

                                            return [
                                                $gradeBenefit->id => "{$benefitName} - {$gradeName} - Rp {$amount}"
                                            ];
                                        })->toArray();
                                    })
                                    ->disabled(fn(Get $get): bool => !$get('../../employee_id'))
                                    ->validationMessages([
                                        'required' => 'Silakan pilih tunjangan yang sesuai',
                                    ]),
                            ])
                            ->columns(1)
                            ->itemLabel(
                                fn(array $state): ?string =>
                                $state['benefit_id']
                                    ? MasterEmployeeGradeBenefit::find($state['benefit_id'])?->benefit?->name ?? 'Tunjangan'
                                    : 'Tunjangan'
                            )
                            ->addActionLabel('Tambah Tunjangan')
                            ->defaultItems(0)
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->cloneable()
                            ->maxItems(5),

                        Forms\Components\Hidden::make('users_id')
                            ->default(fn() => auth()->id())
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Dapatkan semua jenis tunjangan yang unik
        $benefitTypes = MasterEmployeeGradeBenefit::with('benefit')
            ->get()
            ->pluck('benefit.name')
            ->unique()
            ->values()
            ->toArray();

        // Buat kolom dasar
        $columns = [
            Tables\Columns\TextColumn::make('employee.name')
                ->label('Nama Pegawai')
                ->searchable()
                ->sortable()
                ->description(fn(EmployeeBenefit $record): string => $record->employee?->nippam ?? ''),

            Tables\Columns\TextColumn::make('employee.grade.name')
                ->label('Golongan')
                ->searchable()
                ->sortable()
                ->badge()
                ->color('success'),
        ];

        // Tambahkan kolom untuk setiap jenis tunjangan
        foreach ($benefitTypes as $benefitType) {
            $columns[] = Tables\Columns\TextColumn::make("benefit_{$benefitType}")
                ->label($benefitType)
                ->alignRight()
                ->money('IDR')
                ->state(function (EmployeeBenefit $record) use ($benefitType): int {
                    if (!is_array($record->benefits)) {
                        return 0;
                    }

                    return collect($record->benefits)
                        ->map(function ($benefit) use ($benefitType) {
                            $masterBenefit = MasterEmployeeGradeBenefit::find($benefit['benefit_id']);
                            if (!$masterBenefit || $masterBenefit->benefit->name !== $benefitType) {
                                return 0;
                            }
                            return $masterBenefit->amount ?? 0;
                        })
                        ->sum();
                });
        }

        // Tambahkan kolom total di akhir
        $columns[] = Tables\Columns\TextColumn::make('total_amount')
            ->label('Total')
            ->money('IDR')
            ->alignRight()
            ->state(function (EmployeeBenefit $record): int {
                if (!is_array($record->benefits)) {
                    return 0;
                }

                return collect($record->benefits)->sum(function ($benefit) {
                    $masterBenefit = MasterEmployeeGradeBenefit::find($benefit['benefit_id']);
                    return $masterBenefit?->amount ?? 0;
                });
            });

        return $table
            ->columns($columns)
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with(['employee.grade']);
            })
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('employee')
                    ->relationship('employee', 'name')
                    ->label('Pegawai')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Tables\Filters\SelectFilter::make('grade')
                    ->relationship('employee.grade', 'name')
                    ->label('Golongan')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Tables\Filters\TrashedFilter::make()
                    ->label('Status Data')
                    ->trueLabel('Tampilkan data terhapus')
                    ->falseLabel('Sembunyikan data terhapus'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat')
                        ->color('info'),

                    Tables\Actions\EditAction::make()
                        ->label('Edit'),

                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Tunjangan Pegawai')
                        ->modalDescription('Apakah Anda yakin ingin menghapus tunjangan pegawai ini? Data yang sudah dihapus dapat dipulihkan.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),

                    Tables\Actions\RestoreAction::make()
                        ->label('Pulihkan'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-m-chevron-down')
                    ->size('sm')
                    ->color('primary'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus yang dipilih')
                        ->modalHeading('Hapus Tunjangan Pegawai')
                        ->modalDescription('Apakah Anda yakin ingin menghapus tunjangan pegawai yang dipilih? Data yang sudah dihapus dapat dipulihkan.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan yang dipilih'),
                ]),
            ])
            ->emptyStateHeading('Belum ada data tunjangan')
            ->emptyStateDescription('Mulai tambahkan tunjangan pegawai dengan klik tombol di bawah ini')
            ->emptyStateIcon('heroicon-o-banknotes')
            ->striped()
            ->paginated([10, 25, 50])
            ->poll('10s');
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
            'index' => Pages\ListEmployeeBenefits::route('/'),
            'create' => Pages\CreateEmployeeBenefit::route('/create'),
            'edit' => Pages\EditEmployeeBenefit::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
