<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeBenefitResource\Pages;
use App\Filament\Resources\EmployeeBenefitResource\RelationManagers;
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
use Filament\Forms\Get;  // Tambahkan import ini di bagian atas file
use Filament\Forms\Set;  // Jika Anda juga menggunakan Set
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeBenefitResource extends Resource
{
    protected static ?string $model = EmployeeBenefit::class;
    protected static ?string $navigationLabel = 'Tunjangan Pegawai';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Nama Pegawai')
                    ->required(),
                Select::make('employee_grade_benefit_id')
                    ->label('Pilih Tunjangan Sesuai Golongan')
                    ->placeholder('Pilih Tunjangan...')
                    ->required()
                    ->searchable()
                    ->options(function (Get $get) {
                        $employeeId = $get('employee_id');

                        if (!$employeeId) {
                            return [];
                        }

                        $employee = \App\Models\Employees::with(['employeeGrade'])->find($employeeId);
                        $gradeId = $employee?->employee_grade_id;

                        if (!$gradeId) {
                            return [];
                        }

                        return MasterEmployeeGradeBenefit::query()
                            ->where('grade_id', $gradeId)
                            ->with(['gradeBenefits', 'benefit'])
                            ->get()
                            ->mapWithKeys(function ($gradeBenefit) {
                                $gradeName = $gradeBenefit->gradeBenefits->name ?? '';
                                $benefitName = $gradeBenefit->benefit->name ?? '';
                                $amount = number_format($gradeBenefit->amount, 2);

                                return [
                                    $gradeBenefit->id => "{$gradeName} - {$benefitName} - Rp {$amount}"
                                ];
                            })
                            ->toArray();
                    })
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        // Optional: Tambahkan logic tambahan jika diperlukan setelah value berubah
                    })
                    ->reactive(),
                Forms\Components\Hidden::make('users_id')
                    ->default(auth()->id()),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee_grade_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee_benefit_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListEmployeeBenefits::route('/'),
            'create' => Pages\CreateEmployeeBenefit::route('/create'),
            'edit' => Pages\EditEmployeeBenefit::route('/{record}/edit'),
        ];
    }
}
