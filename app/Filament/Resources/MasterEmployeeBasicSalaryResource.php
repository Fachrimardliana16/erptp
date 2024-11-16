<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterEmployeeBasicSalaryResource\Pages;
use App\Filament\Resources\MasterEmployeeBasicSalaryResource\RelationManagers;
use App\Models\MasterEmployeeBasicSalary;
use App\Models\MasterEmployeeGrade;
use App\Models\MasterEmployeeServiceGrade;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterEmployeeBasicSalaryResource extends Resource
{
    protected static ?string $model = MasterEmployeeBasicSalary::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationGroup = 'Master Employee';
    protected static ?string $navigationLabel = 'Tabel Gaji Pokok';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Gaji Pokok')
                    ->description('Input gaji pokok pada form di bawah ini.')
                    ->schema([
                        Forms\Components\Select::make('employee_service_grade_id')
                            ->options(function () {
                                return MasterEmployeeServiceGrade::query()
                                    ->with('employeeGrade')
                                    ->select([
                                        'master_employee_service_grade.id',
                                        'master_employee_service_grade.service_grade',
                                        'master_employee_grade.name as grade_name'
                                    ])
                                    ->join(
                                        'master_employee_grade',
                                        'master_employee_service_grade.employee_grade_id',
                                        '=',
                                        'master_employee_grade.id'
                                    )
                                    ->orderBy('master_employee_grade.name')
                                    ->orderBy('master_employee_service_grade.service_grade')
                                    ->get()
                                    ->mapWithKeys(function ($serviceGrade) {
                                        return [
                                            $serviceGrade->id => "Golongan {$serviceGrade->grade_name} - MKG: {$serviceGrade->service_grade}"
                                        ];
                                    });
                            })
                            ->afterStateUpdated(function ($set, $state) {
                                $serviceGrade = MasterEmployeeServiceGrade::with('employeeGrade')
                                    ->find($state);

                                if ($serviceGrade) {
                                    $set('employee_grade_id', $serviceGrade->employee_grade_id);
                                    $set('service_grade', $serviceGrade->service_grade);
                                }
                            })
                            ->label('Pilih Golongan beserta MKG..')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),

                        Forms\Components\Hidden::make('employee_grade_id'),

                        Forms\Components\TextInput::make('service_grade')
                            ->label('MKG')
                            ->disabled(),

                        Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->required(),
                        Forms\Components\Textarea::make('desc')
                            ->label('Keterangan'),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('serviceGrade.employeeGrade.name')
                    ->label('Golongan')
                    ->sortable(['master_employee_grade.name'])
                    ->searchable()
                    ->formatStateUsing(fn($state) => "Golongan {$state}"),

                Tables\Columns\TextColumn::make('serviceGrade.service_grade')
                    ->label('MKG')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('desc')
                    ->label('Keterangan')
                    ->sortable()
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
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMasterEmployeeBasicSalaries::route('/'),
            //'create' => Pages\CreateMasterEmployeeBasicSalary::route('/create'),
            //'edit' => Pages\EditMasterEmployeeBasicSalary::route('/{record}/edit'),
        ];
    }
}
