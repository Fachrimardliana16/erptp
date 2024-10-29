<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeMutationsResource\Pages;
use App\Filament\Resources\EmployeeMutationsResource\RelationManagers;
use App\Models\EmployeeMutations;
use App\Models\Employees;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeMutationsResource extends Resource
{
    protected static ?string $model = EmployeeMutations::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Mutasi';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Mutasi Pegawai')
                    ->description('Form input mutasi pegawai')
                    ->schema([
                        Forms\Components\TextInput::make('decision_letter_number')
                            ->label('Nomor Surat Keputusan')
                            ->required(),
                        Forms\Components\DatePicker::make('mutation_date')
                            ->label('Tanggal Mutasi')
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->options(Employees::query()->pluck('name', 'id'))
                            ->afterStateUpdated(function ($set, $state) {
                                $employees = Employees::find($state);
                                $set('name', $employees->name);
                                $set('old_department_id', $employees->departments_id);
                                $set('old_sub_department_id', $employees->sub_department_id);
                                $set('old_position_id', $employees->employees_position_id);
                            })
                            ->label('Nama Pegawai')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Hidden::make('employee_id')
                            ->label('Nama Pegawai')
                            ->required(),
                        Forms\Components\Select::make('old_department_id')
                            ->relationship('oldDepartment', 'name')
                            ->label('Bagian Lama')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('old_department_id')
                            ->label('Bagian Lama')
                            ->required(),
                        Forms\Components\Select::make('old_sub_department_id')
                            ->relationship('oldSubDepartment', 'name')
                            ->label('Sub Bagian Lama')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('old_sub_department_id')
                            ->label('Sub Bagian Lama')
                            ->required(),
                        Forms\Components\Select::make('new_department_id')
                            ->relationship('newDepartment', 'name')
                            ->label('Bagian Baru')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('new_department_id')
                            ->label('Bagian Baru')
                            ->required(),
                        Forms\Components\Select::make('new_sub_department_id')
                            ->relationship('newSubDepartment', 'name')
                            ->label('Sub Bagian Baru')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('new_sub_department_id')
                            ->label('Sub Bagian Baru')
                            ->required(),
                        Forms\Components\Select::make('old_position_id')
                            ->relationship('oldPosition', 'name')
                            ->label('Jabatan Lama')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('old_position_id')
                            ->label('Jabatan Lama')
                            ->required(),
                        Forms\Components\Select::make('new_position_id')
                            ->relationship('newPosition', 'name')
                            ->label('Jabatan Baru')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('new_position_id')
                            ->label('Jabatan Baru')
                            ->required(),
                        Forms\Components\FileUpload::make('docs')
                            ->label('Lampiran Surat')
                            ->required(),
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
                Tables\Columns\TextColumn::make('decision_letter_number')
                    ->label('Nomor SK')
                    ->sortable(),
                Tables\Columns\TextColumn::make('mutation_date')
                    ->label('Tanggal Mutasi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employeeMutation.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('oldDepartment.name')
                    ->label('Bagian Lama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('oldSubDepartment.name')
                    ->label('Sub Bagian Lama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('newDepartment.name')
                    ->label('Bagian Baru')
                    ->searchable(),
                Tables\Columns\TextColumn::make('newSubDepartment.name')
                    ->label('Sub Bagian Baru')
                    ->searchable(),
                Tables\Columns\TextColumn::make('oldPosition.name')
                    ->label('Jabatan Lama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('newPosition.name')
                    ->label('Jabatan Baru')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('docs')
                    ->label('Lampiran Surat')
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
            'index' => Pages\ListEmployeeMutations::route('/'),
            'create' => Pages\CreateEmployeeMutations::route('/create'),
            'edit' => Pages\EditEmployeeMutations::route('/{record}/edit'),
        ];
    }
}
