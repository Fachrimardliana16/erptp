<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetDisposalResource\Pages;
use App\Models\AssetDisposal;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;

class AssetDisposalResource extends Resource
{
    protected static ?string $model = AssetDisposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Penghapusan Aset';
    protected static ?int $navigationSort = 8;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Penghapusan Aset')
                    ->description('Input data penghapusan aset')
                    ->schema([
                        Forms\Components\TextInput::make('disposals_number')
                            ->label('Nomor Penghapusan Aset')
                            ->required(),
                        Forms\Components\DatePicker::make('disposal_date')
                            ->label('Tanggal Penghapusan')
                            ->required(),
                        Forms\Components\Select::make('assets_id')
                            ->relationship('assetDisposals', 'name')
                            ->label('Nama Aset')
                            ->required(),
                        Forms\Components\TextInput::make('book_value')
                            ->label('Nilai Buku')
                            ->required()
                            ->prefix('Rp. ')
                            ->numeric(),
                        Forms\Components\Textarea::make('disposal_reason')
                            ->label('Alasan Penghapusan')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('disposal_value')
                            ->label('Nilai Penghapusan')
                            ->required()
                            ->prefix('Rp. ')
                            ->numeric(),
                        Forms\Components\Select::make('disposal_process')
                            ->label('Proses Penghapusan')
                            ->options([
                                'dihapus secara fisik' => 'Dihapus Secara Fisik',
                                'dihapus dari daftar inventaris' => 'Dihapus Dari Daftar Inventaris',
                                'dijual' => 'Dijual',
                            ])
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('employee_id')
                            ->options(
                                Employees::query()
                                    ->select(['employees.id', 'employees.name'])
                                    ->join('master_employee_position', 'employees.employee_position_id', '=', 'master_employee_position.id')
                                    ->where('master_employee_position.name', 'like', '%kepala bagian umum%')
                                    ->get()
                                    ->mapWithKeys(function ($employee) {
                                        return [$employee->id => $employee->name];
                                    })
                                    ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->label('Pejabat Mengetahui')
                            ->required(),
                        Forms\Components\Textarea::make('disposal_notes')
                            ->label('Catatan Penghapusan')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('docs')
                            ->directory('Asset_Disposal')
                            ->required()
                            ->label('Lampiran Surat Keputusan')
                            ->rules(['required', 'mimes:pdf', 'max:5240'])
                            ->helperText('Unggah dokumen (SK/Surat Tugas/Memo Direksi) dengan format ".pdf" maksimal ukuran file 5MB.'),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\BulkAction::make('Export Report') // Action untuk download PDF yang sudah difilter
                    ->icon('heroicon-m-arrow-down-tray')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        // Ambil data karyawan yang memiliki jabatan 'Kepala Sub Bagian Kerumahtanggaan'
                        $employee = Employees::whereHas('employeePosition', function ($query) {
                            $query->where('name', 'Kepala Sub Bagian Kerumahtanggaan');
                        })->first();

                        // Cek apakah pegawai ditemukan
                        if (!$employee) {
                            // Menampilkan notifikasi kesalahan
                            Notification::make()
                                ->title('Kesalahan')
                                ->danger() // Menandakan bahwa ini adalah notifikasi kesalahan
                                ->body('Tidak ada pegawai dengan jabatan Kepala Sub Bagian Kerumahtanggaan.')
                                ->persistent() // Notifikasi akan tetap muncul sampai ditutup oleh pengguna
                                ->send();
                            return;
                        }
                        // Render PDF dengan data records dan employee
                        return response()->streamDownload(function () use ($records, $employee) {
                            $pdfContent = Blade::render('pdf.report_asset_disposal', [
                                'records' => $records,
                                'employee' => $employee
                            ]);
                            echo Pdf::loadHTML($pdfContent)
                                ->setPaper('A4', 'landscape') // Set ukuran kertas dan orientasi
                                ->stream();
                        }, 'disposal_assets.pdf');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('disposal_date')
                    ->label('Tanggal Penghapusan')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('assetDisposals.name')
                    ->label('Nama Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('book_value')
                    ->label('Nilai Buku')
                    ->money('Rp. ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('disposal_reason')
                    ->label(' Alasan Penghapusan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disposal_value')
                    ->label('Nilai Penghapusan')
                    ->money('Rp. ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('disposal_process')
                    ->label('Proses Penghapusan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employeeDisposals.name')
                    ->label('Pejabat Mengetahui')
                    ->searchable(),
                Tables\Columns\TextColumn::make('disposal_notes')
                    ->label('Catatan Penghapusan')
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
                Filter::make('Tanggal')
                    ->form([
                        DatePicker::make('Dari'),
                        DatePicker::make('Sampai'),
                    ])
            ], FiltersLayout::Modal)
            ->actions([
                Tables\Actions\EditAction::make(),
                // Action untuk download pdf aset per record
                Action::make('download_pdf')
                    ->label('Cetak Surat')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        $pdf = app(abstract: DomPDF::class);
                        $pdf->loadView('pdf.surat_hapus_asset', ['assetdisposal' => $record]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'hapus_asset-' . $record->assetDisposals->name . '.pdf');
                    })
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
            'index' => Pages\ListAssetDisposals::route('/'),
            'create' => Pages\CreateAssetDisposal::route('/create'),
            'edit' => Pages\EditAssetDisposal::route('/{record}/edit'),
        ];
    }
}
