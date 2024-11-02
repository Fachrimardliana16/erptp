<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeMutationsResource\Pages;
use App\Models\EmployeeMutations;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Barryvdh\DomPDF\PDF as DomPDF;

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
                        TextInput::make('decision_letter_number')
                            ->label('Nomor Surat Keputusan')
                            ->required()
                            ->validationAttribute('Nomor Surat Keputusan')
                            ->rules('unique:employee_mutations,decision_letter_number')
                            ->helperText('Masukkan nomor surat keputusan yang unik dan valid.'),

                        DatePicker::make('mutation_date')
                            ->label('Tanggal Mutasi')
                            ->required()
                            ->validationAttribute('Tanggal Mutasi')
                            ->rules('date_format:Y-m-d')
                            ->helperText('Pilih tanggal mutasi sesuai format yang benar.'),

                        Select::make('employee_id')
                            ->options(Employees::query()->pluck('name', 'id'))
                            ->afterStateUpdated(function ($set, $state) {
                                $employees = Employees::find($state);
                                $set('old_department_id', $employees->departments_id);
                                $set('old_sub_department_id', $employees->sub_department_id);
                                $set('old_position_id', $employees->employee_position_id);
                            })
                            ->label('Nama Pegawai')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),

                        Select::make('old_department_id')
                            ->disabled()
                            ->relationship('oldDepartment', 'name')
                            ->label('Bagian Lama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('old_sub_department_id')
                            ->disabled()
                            ->relationship('oldSubDepartment', 'name')
                            ->label('Sub Bagian Lama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('old_position_id')
                            ->disabled()
                            ->relationship('oldPosition', 'name')
                            ->label('Jabatan Lama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('new_department_id')
                            ->relationship('newDepartment', 'name')
                            ->label('Bagian Baru')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('new_sub_department_id', null)),

                        Select::make('new_sub_department_id')
                            ->relationship(
                                'newSubDepartment',
                                'name',
                                fn(Builder $query, callable $get) => $query
                                    ->when(
                                        $get('new_department_id'),
                                        fn(Builder $q, $departmentId) =>
                                        $q->where('departments_id', $departmentId)
                                    )
                            )
                            ->label('Sub Bagian Baru')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn(callable $get) => !$get('new_department_id')),

                        Select::make('new_position_id')
                            ->relationship('newPosition', 'name')
                            ->label('Jabatan Baru')
                            ->searchable()
                            ->preload()
                            ->required(),

                        FileUpload::make('docs')
                            ->label('Lampiran Surat')
                            ->required(),

                        Hidden::make('users_id')
                            ->default(auth()->id())
                            ->required(),

                        Hidden::make('users_id')
                            ->default(auth()->id())
                            ->required()
                            ->validationAttribute('Users ID') // Validasi atribut untuk error handling
                            ->helperText('ID pengguna saat ini akan disimpan di sini.')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\BulkAction::make('Export Pdf')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        // Ambil data karyawan yang memiliki jabatan 'Kepala Sub Bagian Kepegawaian'
                        $employees = Employees::whereHas('employeePosition', function ($query) {
                            $query->where('name', 'Kepala Sub Bagian Kepegawaian');
                        })->first();

                        // Render PDF dengan data records dan employee
                        return response()->streamDownload(function () use ($records, $employees) {
                            $pdfContent = Blade::render('pdf.report_employee_mutation', [
                                'records' => $records,
                                'employees' => $employees
                            ]);
                            
                            // Load HTML ke dalam PDF dengan orientasi landscape
                            $pdf = Pdf::loadHTML($pdfContent)->setPaper('a4', 'landscape');
                            
                            echo $pdf->stream();
                        }, 'report_employee_mutation.pdf');
                    }),
            ])
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
                SelectFilter::make('Nama Pegawai')
                ->relationship('employeeMutation', 'name'),
                Filter::make('Tanggal')
                ->form([
                    DatePicker::make('Dari'),
                    DatePicker::make('Sampai'),
                ])
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
