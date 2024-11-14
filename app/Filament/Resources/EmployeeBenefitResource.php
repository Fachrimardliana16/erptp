<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeBenefitResource\Pages;
use App\Filament\Resources\EmployeeBenefitResource\RelationManagers;
use App\Models\EmployeeBenefit;
use App\Models\Employees;
use App\Models\MasterEmployeeBenefit;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeBenefitResource extends Resource
{
    protected static ?string $model = EmployeeBenefit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->required(),
                Select::make('employee_grade_benefit_id')
                    ->label('Pilih Employee Grade Benefit') // Label for the dropdown
                    ->placeholder('Pilih Benefit...') // Placeholder text
                    ->required()
                    ->searchable() // Enable searching through the select options
                    ->options(function () {
                        // Get the employee_id from the request or context
                        $employeeId = request()->input('employee_id'); // Adjust this according to your context

                        // Check if the employee_id has an employee_grade_id
                        $employee = Employees::with('employeeGrade')->find($employeeId);

                        if ($employee && $employee->employee_grade_id) {
                            // If there is an employee_grade_id, get the grade_id
                            $gradeId = $employee->employee_grade_id;

                            // Fetch benefits based on the grade_id
                            return MasterEmployeeBenefit::query()
                                ->where('grade_id', $gradeId) // Filter by grade_id
                                ->get()
                                ->mapWithKeys(function ($benefit) {
                                    return [
                                        $benefit->benefit_id => $benefit->name // Use benefit_id as the key and name as the value
                                    ];
                                });
                        }

                        // Return an empty array if no employee_grade_id is found
                        return [];
                    }),
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
