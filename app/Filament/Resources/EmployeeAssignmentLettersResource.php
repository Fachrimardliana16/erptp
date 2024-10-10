<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Employees;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Section;
use App\Models\EmployeeAssignmentLetters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeAssignmentLettersResource\Pages;
use App\Filament\Resources\EmployeeAssignmentLettersResource\RelationManagers;

use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;

class EmployeeAssignmentLettersResource extends Resource
{
    protected static ?string $model = EmployeeAssignmentLetters::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Surat Tugas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Surat Tugas')
                    ->description('Input data surat tugas.')
                    ->schema([
                        Forms\Components\TextInput::make('registration_number')
                            ->label('Nomor Surat')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('assigning_employee_id')
                            ->options(Employees::query()->pluck('name', 'id'))
                            ->afterStateUpdated(function ($set, $state) {
                                $employees = Employees::find($state);
                                $set('employee_position_id', $employees->employee_position_id);
                            })
                            ->relationship('aassigningEmployee', 'name')
                            ->label('Pemberi Tugas')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\Select::make('employee_position_id')
                            ->relationship('positionAssign', 'name')
                            ->label('Jabatan')
                            ->required(),
                        Forms\Components\Select::make('assigned_employee_id')
                            ->multiple()
                            ->relationship('assignedEmployee', 'name')
                            ->label('Penerima Tugas')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),
                        Forms\Components\Textarea::make('task')
                            ->label('Detail Tugas')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Tambahan')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_number')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aassigningEmployee.name')
                    ->label('Pemberi Tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('positionAssign.name')
                    ->label('Jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignedEmployee.name')
                    ->label('Penerima Tugas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('task')
                    ->label('Tugas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
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
                    // Action untuk download pdf aset per record
                    Action::make('download_pdf')
                        ->label('Download PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($record) {
                            $pdf = app(abstract: DomPDF::class);
                            $pdf->loadView('pdf.employee_assignmentletter', ['surat_tugas' => $record]);

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'surat_tugas-' . $record->name . '.pdf');
                        }),
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
            'index' => Pages\ListEmployeeAssignmentLetters::route('/'),
            'create' => Pages\CreateEmployeeAssignmentLetters::route('/create'),
            'edit' => Pages\EditEmployeeAssignmentLetters::route('/{record}/edit'),
        ];
    }
}
