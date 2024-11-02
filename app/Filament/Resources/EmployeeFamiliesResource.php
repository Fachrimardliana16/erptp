<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeFamiliesResource\Pages;
use App\Filament\Resources\EmployeeFamiliesResource\RelationManagers;
use App\Models\EmployeeFamilies;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;

class EmployeeFamiliesResource extends Resource
{
    protected static ?string $model = EmployeeFamilies::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Keluarga Pegawai';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Monitoring Aset')
                    ->description('Input data monitoring aset')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employeeFamilies', 'name')
                            ->label('Nama Pegawai')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('family_id')
                            ->relationship('families', 'name')
                            ->label('Status Keluarga')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Keluarga')
                            ->required()
                            ->maxLength(255)
                            ->columnspanfull(),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'Laki-Laki' => 'Laki-Laki',
                                'Perempuran' => 'Perempuran',
                            ])
                            ->label('Jenis Kelamin')
                            ->required()
                            ->columnspanfull(),
                        Forms\Components\TextInput::make('place_birth')
                            ->label('Tempat Lahir')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date_birth')
                            ->label('Tanggal Lahir'),
                        Forms\Components\TextInput::make('id_number')
                            ->label('NIK')
                            ->numeric()
                            ->maxLength(16)
                            ->columnspanfull(),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->headerActions([
            Tables\Actions\BulkAction::make('Export Pdf') // Action untuk download PDF yang sudah difilter
                ->icon('heroicon-m-arrow-down-tray')
                ->deselectRecordsAfterCompletion()
                ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                    // Ambil data karyawan yang memiliki jabatan 'Kepala Sub Bagian Kepegawaian'
                    $employees = Employees::whereHas('employeePosition', function ($query) {
                        $query->where('name', 'Kepala Sub Bagian Kepegawaian');
                    })->first();

                    // Render PDF dengan data records dan employee
                    return response()->streamDownload(function () use ($records, $employees) {
                        $pdfContent = Blade::render('pdf.report_employee_families', [
                            'records' => $records,
                            'employees' => $employees
                        ]);
                        echo Pdf::loadHTML($pdfContent)->stream();
                    }, 'report_employee_families_' . ($records->first()->employeeFamilies->name ?? 'unknown') . '.pdf');
                }),
        ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('employeeFamilies.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('families.name')
                    ->label('Status Keluarga')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_number')
                    ->label('NIK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Keluarga')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('place_birth')
                    ->label('Tempat Lahir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_birth')
                    ->label('Tanggal Lahir')
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
                SelectFilter::make('Nama Pegawai')
                    ->relationship('employeeFamilies', 'name'),
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
            'index' => Pages\ListEmployeeFamilies::route('/'),
            'create' => Pages\CreateEmployeeFamilies::route('/create'),
            'edit' => Pages\EditEmployeeFamilies::route('/{record}/edit'),
        ];
    }
}
