<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeePromotionResource\Pages;
use App\Filament\Resources\EmployeePromotionResource\RelationManagers;
use App\Models\EmployeePromotion;
use App\Models\Employees;
use App\Models\MasterEmployeeBasicSalary;
use App\Models\MasterEmployeeGrade;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;

class EmployeePromotionResource extends Resource
{
    protected static ?string $model = EmployeePromotion::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Kenaikan Golongan';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Kenaikan Golongan Pegawai')
                    ->description('Form input kenaikan golongan pegawai')
                    ->schema([
                        Forms\Components\TextInput::make('decision_letter_number')
                            ->label('Nomor Surat Keputusan')
                            ->required(),
                        Forms\Components\DatePicker::make('promotion_date')
                            ->label('Tanggal Kenaikan Golongan')
                            ->required(),
                        Forms\Components\Select::make('employee_id')
                            ->options(Employees::query()->pluck('name', 'id'))
                            ->afterStateUpdated(function ($set, $state) {
                                $employees = Employees::find($state);
                                $set('name', $employees->name);
                                $set('old_grade_id', $employees->employee_grade_id);
                                $set('old_basic_salary', $employees->basic_salary);
                            })
                            ->label('Nama Pegawai')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Hidden::make('employee_id')
                            ->label('Nama Pegawai')
                            ->required(),
                        Forms\Components\Select::make('old_grade_id')
                            ->relationship('oldGrade', 'name')
                            ->label('Golongan Lama')
                            ->searchable()
                            ->preload()
                            ->required() // Keep this required
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Set the hidden field value to the selected old_grade_id
                                $set('old_grade_id_hidden', $state);
                            }),

                        Forms\Components\Hidden::make('old_grade_id_hidden') // Hidden field for saving the value
                            ->label('Old Grade ID (Hidden)')
                            ->dehydrated(),

                        Forms\Components\Select::make('old_basic_salary')
                            ->relationship('oldBasicSalary', 'amount')
                            ->label('Gaji Pokok Lama')
                            ->prefix('Rp. ')
                            ->searchable()
                            ->preload()
                            ->required(), // Keep this required

                        Forms\Components\Hidden::make('old_basic_salary') // Hidden field for saving the value
                            ->label('Gaji Pokok Lama (Hidden)')
                            ->dehydrated(),

                        Forms\Components\Select::make('new_grade_id')
                            ->label('Golongan Baru')
                            ->searchable()
                            ->options(MasterEmployeeBasicSalary::query()->pluck('name', 'id'))  // Kembalikan ke id
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $basicSalary = MasterEmployeeBasicSalary::find($state);  // Query tetap menggunakan id
                                    $set('new_basic_salary', $basicSalary?->amount);
                                    $set('new_basic_salary_hidden', $basicSalary?->amount);
                                } else {
                                    $set('new_basic_salary', null);
                                    $set('new_basic_salary_hidden', null);
                                }
                            }),

                        Forms\Components\TextInput::make('new_basic_salary')
                            ->label('Gaji Pokok Baru')
                            ->prefix('Rp. ')
                            ->numeric()
                            ->required()
                            ->live()
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Hidden::make('new_basic_salary_hidden') // Hidden field for saving the value
                            ->label('Gaji Pokok Baru (Hidden)')
                            ->dehydrated(), // Ensure it's submitted with the form

                        Forms\Components\FileUpload::make('doc_promotion')
                            ->directory('Employee_Promotion')
                            ->label('Berkas Kenaikan Golongan')
                            ->required()
                            ->validationAttribute('Berkas Kenaikan Golongan')
                            ->rules('required|mimes:pdf|max:5024')
                            ->helperText('Hanya file dengan format .pdf yang diperbolehkan. Maksimal ukuran file 5MB'),
                        Forms\Components\TextArea::make('desc')
                            ->label('Catatan Kenaikan Golongan'),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
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
        
                    // Cek apakah pegawai ditemukan
                    if (!$employees) {
                        Notification::make()
                            ->title('Kesalahan')
                            ->danger()
                            ->body('Tidak ada pegawai dengan jabatan Kepala Sub Bagian Kepegawaian.')
                            ->persistent()
                            ->send();
                        return;
                    }
        
                    // Render PDF dengan data records dan employee
                    return response()->streamDownload(function () use ($records, $employees) {
                        $pdfContent = Blade::render('pdf.report_employee_promotion', [
                            'records' => $records,
                            'employees' => $employees
                        ]);
                        echo Pdf::loadHTML($pdfContent)->stream();
                    }, 'report_employee_promotion.pdf');
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
                Tables\Columns\TextColumn::make('promotion_date')
                    ->label('Tanggal Mutasi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Nama Pegawai')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('oldGrade.name')
                    ->label('Golongan Lama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('oldBasicSalary.amount')
                    ->label('Gaji Pokok Lama')
                    ->sortable()
                    ->money('Rp. ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('newGrade.name')
                    ->label('Golongan Baru')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('newBasicSalary.amount')
                    ->label('Gaji Pokok Baru')
                    ->money('Rp. ')
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
            'index' => Pages\ListEmployeePromotions::route('/'),
            'create' => Pages\CreateEmployeePromotion::route('/create'),
            'edit' => Pages\EditEmployeePromotion::route('/{record}/edit'),
        ];
    }
}
