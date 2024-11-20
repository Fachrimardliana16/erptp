<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterEmployeeGradeBenefitResource\Pages;
use App\Models\MasterEmployeeGrade;
use App\Models\MasterEmployeeGradeBenefit;
use App\Models\MasterEmployeeBenefit;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MasterEmployeeGradeBenefitResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Master Employee';
    protected static ?string $navigationLabel = 'Golongan Tunjangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Golongan Tunjangan')
                    ->description('Kelola data golongan tunjangan')
                    ->schema([
                        Forms\Components\Select::make('grade_id')
                            ->options(
                                MasterEmployeeGrade::all()
                                    ->sortBy(function ($grade) {
                                        return [
                                            $grade->name,
                                        ];
                                    })
                                    ->mapWithKeys(function ($grade) {
                                        return [$grade->id => "{$grade->name}"];
                                    })
                            )
                            ->label('Golongan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationMessages([
                                'required' => 'Silakan pilih golongan',
                            ])
                            ->helperText('Pilih golongan untuk menambahkan tunjangan'),

                        Forms\Components\Repeater::make('benefits')
                            ->schema([
                                Forms\Components\Select::make('benefit_id')
                                    ->options(function (Select $component) {
                                        // Dapatkan repeater parent
                                        $repeater = $component->getContainer()->getParentComponent();

                                        // Dapatkan semua benefit_id yang sudah dipilih
                                        $selectedBenefits = collect($repeater->getState() ?? [])
                                            ->pluck('benefit_id')
                                            ->filter()
                                            ->toArray();

                                        // Jika dalam mode edit, exclude benefit_id saat ini
                                        $currentState = $component->getState();
                                        if ($currentState) {
                                            $selectedBenefits = array_diff($selectedBenefits, [$currentState]);
                                        }

                                        // Return benefit yang belum dipilih
                                        return MasterEmployeeBenefit::query()
                                            ->whereNotIn('id', $selectedBenefits)
                                            ->pluck('name', 'id');
                                    })
                                    ->label('Tunjangan')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Select $component) {
                                        $component->getContainer()->getParentComponent()->callAfterStateUpdated();
                                    })
                                    ->validationMessages([
                                        'required' => 'Silakan pilih tunjangan',
                                    ]),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Jumlah')
                                    ->required()
                                    ->validationMessages([
                                        'required' => 'Silakan masukkan jumlah tunjangan',
                                    ])
                                    ->numeric()
                                    ->prefix('Rp'),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->itemLabel(
                                fn(array $state): ?string =>
                                isset($state['benefit_id'])
                                    ? MasterEmployeeBenefit::find($state['benefit_id'])?->name ?? 'Tunjangan'
                                    : 'Tunjangan'
                            )
                            ->addActionLabel('Tambah Tunjangan')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->cloneable()
                            ->maxItems(15)
                            ->live(),

                        Forms\Components\Textarea::make('desc')
                            ->label('Keterangan')
                            ->columnSpanFull(),

                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Dapatkan semua jenis tunjangan yang unik
        $benefitTypes = MasterEmployeeBenefit::all()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();

        // Buat kolom dasar
        $columns = [
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('gradeBenefits.name')
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
                ->money('IDR')
                ->state(function ($record) use ($benefitType): int {
                    if (!is_array($record->benefits)) {
                        return 0;
                    }

                    return collect($record->benefits)
                        ->filter(function ($benefit) use ($benefitType) {
                            $masterBenefit = MasterEmployeeBenefit::find($benefit['benefit_id']);
                            return $masterBenefit && $masterBenefit->name === $benefitType;
                        })
                        ->sum('amount');
                });
        }

        // Tambahkan kolom total
        $columns[] = Tables\Columns\TextColumn::make('total_benefits')
            ->label('Total')
            ->alignRight()
            ->money('IDR')
            ->state(function ($record): int {
                if (!is_array($record->benefits)) {
                    return 0;
                }
                return collect($record->benefits)->sum('amount');
            })
            ->sortable();

        return $table
            ->columns($columns)
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('grade_id')
                    ->relationship('gradeBenefits', 'name')
                    ->label('Golongan')
                    ->searchable()
                    ->preload()
                    ->multiple(),
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
                        ->modalHeading('Hapus Golongan Tunjangan')
                        ->modalDescription('Apakah Anda yakin ingin menghapus golongan tunjangan ini? Data yang sudah dihapus dapat dipulihkan.')
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
                        ->modalHeading('Hapus Golongan Tunjangan')
                        ->modalDescription('Apakah Anda yakin ingin menghapus golongan tunjangan yang dipilih? Data yang sudah dihapus dapat dipulihkan.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan yang dipilih'),
                ]),
            ])
            ->emptyStateHeading('Belum ada data golongan tunjangan')
            ->emptyStateDescription('Mulai tambahkan golongan tunjangan dengan klik tombol di bawah ini')
            ->emptyStateIcon('heroicon-o-star')
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
            'index' => Pages\ListMasterEmployeeGradeBenefits::route('/'),
            'create' => Pages\CreateMasterEmployeeGradeBenefit::route('/create'),
            'edit' => Pages\EditMasterEmployeeGradeBenefit::route('/{record}/edit'),
        ];
    }
}
