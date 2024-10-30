<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeDailyReportResource\Pages;
use App\Models\EmployeeDailyReport;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class EmployeeDailyReportResource extends Resource
{
    protected static ?string $model = EmployeeDailyReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Employee';
    protected static ?string $navigationLabel = 'Log Laporan Harian Kerja (beta)';
    protected static ?int $navigationSort = 10;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Laporan Harian Kerja')
                    ->description('Input data laporan harian kerja pegawai.')
                    ->schema([
                        Select::make('employee_id')
                            ->relationship('employee', 'name')
                            ->label('Nama Pegawai')
                            ->required(),
                        DatePicker::make('daily_report_date')
                            ->label('Tanggal Laporan Harian Kerja')
                            ->required(),
                        Repeater::make('work_reports')
                            ->label('Pekerjaan')
                            ->schema([
                                Forms\Components\Textarea::make('work_description')
                                    ->label('Uraian Pekerjaan')
                                    ->required()
                                    ->columnSpanFull(),
                                Select::make('work_status')
                                    ->label('Status Pekerjaan')
                                    ->options([
                                        'selesai' => 'Selesai',
                                        'dalam pengerjaan' => 'Dalam Pengerjaan',
                                        'tidak selesai' => 'Tidak Selesai',
                                    ])
                                    ->required(),
                                Forms\Components\Textarea::make('desc')
                                    ->label('Keterangan')
                                    ->columnSpanFull(),
                                FileUpload::make('image')
                                    ->label('Gambar')
                                    ->image(),
                            ])
                            ->createItemButtonLabel('Tambah Rincian Pekerjaan')
                            ->collapsible(),
                        Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('employee.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                TextColumn::make('daily_report_date')
                    ->label('Tanggal Laporan Harian Kerja')
                    ->date()
                    ->sortable(),
                TextColumn::make('work_reports.work_status')
                    ->label('Status Pekerjaan')
                    ->searchable(),
                ImageColumn::make('work_reports.image')
                    ->label('Gambar')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('users_id')
                    ->label('User ID')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
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
            'index' => Pages\ListEmployeeDailyReports::route('/'),
            'create' => Pages\CreateEmployeeDailyReport::route('/create'),
            'edit' => Pages\EditEmployeeDailyReport::route('/{record}/edit'),
        ];
    }
}
