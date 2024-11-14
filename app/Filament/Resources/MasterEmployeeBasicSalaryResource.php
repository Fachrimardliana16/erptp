<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterEmployeeBasicSalaryResource\Pages;
use App\Filament\Resources\MasterEmployeeBasicSalaryResource\RelationManagers;
use App\Models\MasterEmployeeBasicSalary;
use App\Models\MasterEmployeeGrade;
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
    protected static ?string $navigationLabel = 'Gaji Pokok';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Gaji Pokok')
                    ->description('Input gaji pokok pada form di bawah ini.')
                    ->schema([

                        Forms\Components\Select::make('employee_grade_id')
                            ->options(MasterEmployeeGrade::query()->pluck('name', 'id')->map(function ($name, $id) {
                                $serviceGrade = MasterEmployeeGrade::find($id)->service_grade; // Assuming 'service_grade' is a field in your model
                                return "{$name} - {$serviceGrade}"; // Concatenate name and service grade
                            }))
                            ->afterStateUpdated(function ($set, $state) {
                                $data = MasterEmployeeGrade::find($state);
                                $set('name', $data->name);
                                $set('service_grade', $data->service_grade); // Assuming you want to set the service grade too
                            })
                            ->label('Golongan')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Hidden::make('employee_grade_id')
                            ->default(function ($get) {
                                $employee_grade_id = $get('employee_grade_id');
                                return $employee_grade_id;
                            }),

                        Forms\Components\TextInput::make('service_grade')
                            ->default(function ($get) {
                                $employee_grade_id = $get('employee_grade_id');
                                $data = \App\Models\MasterEmployeeGrade::find($employee_grade_id);
                                return $data ? $data->service_grade : null;
                            })
                            ->label('MKG')
                            ->disabled(),  // Disabled untuk memastikan tidak bisa diedit

                        Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric('IDR')
                            ->prefix('Rp'),
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
                Tables\Columns\TextColumn::make('gradeSalary.name')
                    ->label('Golongan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('gradeSalary.service_grade')
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
