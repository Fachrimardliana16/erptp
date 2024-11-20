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

class EmployeeBenefitResource extends Resource
{
    protected static ?string $model = EmployeeBenefit::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Tunjangan Pegawai';
    protected static ?string $modelLabel = 'Tunjangan Pegawai';
    protected static ?string $pluralModelLabel = 'Tunjangan Pegawai';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?int $navigationSort = 3;

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
                                modifyQueryUsing: fn($query) => $query->with(['grade'])
                            )
                            ->label('Nama Pegawai')
                            ->required()
                            ->live()
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $employee = Employees::with('grade')->find($state);
                                if (!$employee || !$employee->grade) {
                                    $set('available_benefits', []);
                                    $set('benefits', []);
                                    return;
                                }

                                $gradeBenefits = MasterEmployeeGradeBenefit::where('grade_id', $employee->grade->id)
                                    ->first();

                                if (!$gradeBenefits || !is_array($gradeBenefits->benefits)) {
                                    $set('available_benefits', []);
                                    $set('benefits', []);
                                    return;
                                }

                                // Set available benefits dengan nominal
                                $benefits = collect($gradeBenefits->benefits)->map(function ($benefit) {
                                    $masterBenefit = MasterEmployeeBenefit::find($benefit['benefit_id']);
                                    return [
                                        'value' => $benefit['benefit_id'],
                                        'label' => $masterBenefit ? sprintf(
                                            '%s - Rp %s',
                                            $masterBenefit->name,
                                            number_format($benefit['amount'], 0, ',', '.')
                                        ) : '',
                                        'amount' => $benefit['amount']
                                    ];
                                })->toArray();

                                $set('available_benefits', $benefits);
                                $set('benefits', []);
                            })
                            ->helperText('Pilih pegawai untuk melihat tunjangan yang tersedia'),

                        Forms\Components\Hidden::make('available_benefits'),

                        Forms\Components\Repeater::make('benefits')
                            ->schema([
                                Forms\Components\Select::make('benefit_id')
                                    ->label('Tunjangan')
                                    ->options(function (Get $get) {
                                        $availableBenefits = collect($get('../../available_benefits'));
                                        $currentBenefits = collect($get('../../benefits'));
                                        $currentValue = $get('benefit_id');

                                        $selectedValues = $currentBenefits
                                            ->pluck('benefit_id')
                                            ->filter()
                                            ->reject(function ($value) use ($currentValue) {
                                                return $value === $currentValue;
                                            })
                                            ->toArray();

                                        return $availableBenefits
                                            ->whereNotIn('value', $selectedValues)
                                            ->pluck('label', 'value');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $availableBenefits = collect($get('../../available_benefits'));
                                        $selectedBenefit = $availableBenefits->firstWhere('value', $state);
                                        if ($selectedBenefit) {
                                            $set('amount', $selectedBenefit['amount']);
                                        }
                                    })
                                    ->disabled(fn(Get $get): bool => empty($get('../../employee_id'))),

                                Forms\Components\Hidden::make('amount')
                            ])
                            ->columns(1)
                            ->columnSpanFull()
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Tunjangan')
                            ->reorderable()
                            ->collapsible()
                            ->live(),

                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id())
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $masterBenefits = MasterEmployeeBenefit::all(['id', 'name']);

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

        // Add columns for each benefit type
        foreach ($masterBenefits as $benefit) {
            $columns[] = Tables\Columns\TextColumn::make('benefit_' . $benefit->id)
                ->label($benefit->name)
                ->money('IDR')
                ->state(fn(EmployeeBenefit $record): int => $record->getBenefitAmount($benefit->id));
        }

        // Add total column
        $columns[] = Tables\Columns\TextColumn::make('total_amount')
            ->label('Total')
            ->money('IDR')
            ->state(fn(EmployeeBenefit $record): int => $record->getTotalAmount());

        return $table
            ->columns($columns)
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['employee.grade']))
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
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeBenefits::route('/'),
            'create' => Pages\CreateEmployeeBenefit::route('/create'),
            'edit' => Pages\EditEmployeeBenefit::route('/{record}/edit'),
        ];
    }
}
