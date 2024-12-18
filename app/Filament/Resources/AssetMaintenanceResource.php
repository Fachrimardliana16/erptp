<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetMaintenanceResource\Pages;
use App\Models\AssetMaintenance;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;

class AssetMaintenanceResource extends Resource
{
    protected static ?string $model = AssetMaintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Pemeliharaan Aset';
    protected static ?int $navigationSort = 6;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Pemeliharaan Aset')
                    ->description('Input data pemeliharaan aset')
                    ->schema([
                        Forms\Components\DatePicker::make('maintenance_date')
                            ->label('Tanggal Pemeliharaan')
                            ->required()
                            ->rules('required|date'),

                        Forms\Components\TextInput::make('location_service')
                            ->label('Lokasi')
                            ->required()
                            ->maxLength(255)
                            ->rules('required|string|max:255'),

                        Forms\Components\Select::make('assets_id')
                            ->relationship('AssetMaintenance', 'name')
                            ->label('Nama Aset')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->rules('required|exists:assets,id'),

                        Forms\Components\Select::make('service_type')
                            ->options([
                                'Perbaikian Ringan' => 'Perbaikian Ringan',
                                'Perbaikian Sedang' => 'Perbaikian Sedang',
                                'Perbaikian Berat' => 'Perbaikan Berat',
                                'Pembaruan Dokumen' => 'Pembaruan Dokumen',
                            ])
                            ->label('Tipe Pemeliharaan')
                            ->required()
                            ->rules('required|string|in:Perbaikian Ringan,Perbaikian Sedang,Perbaikian Berat,Pembaruan Dokumen'),

                        Forms\Components\TextInput::make('service_cost')
                            ->label('Total Biaya Pemeliharaan')
                            ->prefix('Rp. ')
                            ->required()
                            ->numeric()
                            ->rules('required|numeric|min:0'),

                        Forms\Components\FileUpload::make('invoice_file')
                            ->directory('Asset_Maintenance')
                            ->label('Bukti Pembayaran Pemeliharaan')
                            ->rules('nullable|file|mimes:jpeg,png,pdf|max:10048')
                            ->helperText('Unggah bukti pembayaran dalam format JPEG, PNG, atau PDF. Maksimal ukuran file adalah 10MB.'),

                        Forms\Components\Textarea::make('desc')
                            ->label('Catatan Pemeliharaan')
                            ->columnSpanFull()
                            ->rules('nullable|string'),

                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id())
                            ->rules('required|exists:users,id'),
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
                            $pdfContent = Blade::render('pdf.report_asset_maintenance', [
                                'records' => $records,
                                'employee' => $employee
                            ]);
                            echo Pdf::loadHTML($pdfContent)->stream();
                        }, 'maintenance_assets.pdf');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('maintenance_date')
                    ->label('Tanggal')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_service')
                    ->label('Lokasi Service')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetMaintenance.name')
                    ->label('Nama Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->label('Jenis Perbaikan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_cost')
                    ->label('Biaya Service')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('invoice_file')
                    ->label('Bukti Pembyaran')
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
            'index' => Pages\ListAssetMaintenances::route('/'),
            'create' => Pages\CreateAssetMaintenance::route('/create'),
            'edit' => Pages\EditAssetMaintenance::route('/{record}/edit'),
        ];
    }
}
