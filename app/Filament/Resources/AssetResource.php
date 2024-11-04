<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Asset;
use Filament\Forms\Form;
use App\Models\Employees;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Barryvdh\DomPDF\PDF as DomPDF;
use Filament\Resources\Pages\Page;
use Endroid\QrCode\Builder\Builder;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Support\Facades\Blade;
use Endroid\QrCode\RoundBlockSizeMode;
use Filament\Support\Enums\ActionSize;
use Filament\Navigation\NavigationItem;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Endroid\QrCode\ErrorCorrectionLevel;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\Pages\EditAsset;
use App\Filament\Resources\AssetResource\Pages\ViewAsset;
use Illuminate\Support\Facades\Validator; // Import Validator
use Illuminate\Validation\ValidationException; // Import ValidationException
use App\Filament\Resources\AssetMonitoringResource\Pages\CreateAssetMonitoring;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Data Aset';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Input Aset Baru')
                    ->description('Form input aset baru')
                    ->schema([
                        Forms\Components\TextInput::make('assets_number')
                            ->label('Nomor Aset')
                            ->required()
                            ->maxLength(255)
                            ->rules('required|string|max:255'),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Aset')
                            ->required()
                            ->maxLength(255)
                            ->rules('required|string|max:255'),

                        Forms\Components\Select::make('category_id')
                            ->relationship('categoryAsset', 'name')
                            ->label('Kategori')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->rules('required|exists:categories,id'),

                        Forms\Components\Select::make('status_id')
                            ->relationship('assetsStatus', 'name')
                            ->label('Status')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->rules('required|exists:assets_statuses,id'),

                        Forms\Components\Select::make('transaction_status_id')
                            ->relationship('AssetTransactionStatus', 'name')
                            ->label('Status Transaksi')
                            ->hidden()
                            ->rules('required|exists:asset_transaction_statuses,id'),

                        Forms\Components\DatePicker::make('purchase_date')
                            ->label('Tanggal Pembelian')
                            ->required()
                            ->default(Carbon::today())
                            ->rules('required|date'),

                        Forms\Components\Select::make('condition_id')
                            ->relationship('conditionAsset', 'name')
                            ->label('Kondisi')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->rules('required|exists:conditions,id'),

                        Forms\Components\TextInput::make('price')
                            ->label('Harga Beli')
                            ->required()
                            ->prefix('Rp. ')
                            ->rules('required|numeric|min:0'),

                        Forms\Components\TextInput::make('funding_source')
                            ->label('Sumber Dana')
                            ->required()
                            ->maxLength(255)
                            ->rules('required|string|max:255'),

                        Forms\Components\TextInput::make('brand')
                            ->label('Merk')
                            ->required()
                            ->maxLength(255)
                            ->rules('required|string|max:255'),

                        Forms\Components\TextInput::make('book_value')
                            ->label('Nilai Buku')
                            ->required()
                            ->maxLength(255)
                            ->prefix('Rp. ')
                            ->rules('required|string|max:255'),

                        Forms\Components\DatePicker::make('book_value_expiry')
                            ->label('Tanggal Habis Buku')
                            ->required()
                            ->rules('required|date'),

                        Forms\Components\DatePicker::make('date_document_extension')
                            ->label('Tanggal Perpanjangan Dokumen')
                            ->rules('required|date'),

                        Forms\Components\Textarea::make('desc')
                            ->columnSpanFull()
                            ->rules('nullable|string'),

                        Forms\Components\FileUpload::make('img')
                            ->directory('Assets')
                            ->label('Gambar')
                            ->rules('nullable|mimes:jpeg,png|max:10240'),

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
                Tables\Actions\BulkAction::make('Export Pdf') // Action untuk download PDF yang sudah difilter
                    ->icon('heroicon-m-arrow-down-tray')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        // Ambil data karyawan yang memiliki jabatan 'Kepala Sub Bagian Kerumahtanggaan'
                        $employee = Employees::whereHas('employeePosition', function ($query) {
                            $query->where('name', 'Kepala Sub Bagian Kerumahtanggaan');
                        })->first();

                        // Cek apakah ada lokasi aset yang terkait dengan records
                        $locationExists = $records->contains(function ($record) {
                            return $record->AssetMutationLocation !== null; // Cek apakah AssetMutationLocation ada
                        });

                        if (!$locationExists) {
                            // Jika tidak ada lokasi aset ditemukan, tampilkan notifikasi error
                            Notification::make()
                                ->title('Error')
                                ->body('Periksa lagi aset yang dipilih. Ditemukan data yang belum memiliki Lokasi dan Sub Lokasi. Masukan pada Mutasi aset untuk menambahkan Lokasi dan Sub Lokasi')
                                ->danger() // Menandakan notifikasi sebagai error
                                ->send();

                            return; // Hentikan eksekusi lebih lanjut
                        }

                        // Render PDF dengan data records dan employee
                        return response()->streamDownload(function () use ($records, $employee) {
                            $pdfContent = Blade::render('pdf.report_asset', [
                                'records' => $records,
                                'employee' => $employee
                            ]);
                            echo Pdf::loadHTML($pdfContent)->stream();
                        }, 'assets.pdf');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
                Tables\Columns\ImageColumn::make('img')
                    ->label('Gambar')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('assets_number')
                    ->label('Nomor Aset')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('categoryAsset.name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_date')
                    ->label('Tanggal Pembelian')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('conditionAsset.name')
                    ->label('Kondisi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->sortable()
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('funding_source')
                    ->label('Sumber Dana')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label('Merk')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('assetsStatus.name')
                    ->label('Status')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetTransactionStatus.name')
                    ->label('Status Transaksi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('book_value')
                    ->label('Nilai Buku')
                    ->sortable()
                    ->money('Rp. ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('book_value_expiry')
                    ->label('Tanggal Habis Nilai Buku')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_document_extension')
                    ->label('Tanggal Perpanjangan Dokumen')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('AssetMutationLocation.name')
                    ->label('Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetMutationSubLocation.name')
                    ->label('Sub Lokasi')
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
                SelectFilter::make('Lokasi')
                    ->relationship('AssetMutationLocation', 'name'),
                SelectFilter::make('Sub Lokasi')
                    ->relationship('AssetMutationSubLocation', 'name'),
                Filter::make('Tanggal')
                    ->form([
                        DatePicker::make('Dari'),
                        DatePicker::make('Sampai'),
                    ])
            ], FiltersLayout::Modal)
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    ViewAction::make(),
                    // Action untuk download pdf aset per record
                    Action::make('download_pdf')
                        ->label('Download PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($record) {
                            $pdf = app(abstract: DomPDF::class);
                            $pdf->loadView('pdf.dataasset', ['asset' => $record]);

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'asset-' . ($record->name ?? 'unknown') . '.pdf');
                        }),

                    Action::make('print_label')
                        ->label('Cetak Label')
                        ->icon('heroicon-o-printer')
                        ->action(function ($record) {
                            // Check if AssetTransactionStatus.name is present
                            if (!$record->transaction_status_id) {
                                // Show error notification
                                Notification::make()
                                    ->title('Kesalahan')
                                    ->body('Aset yang dipilih tidak memiliki status transaksi. Silakan perbarui sebelum mencetak label.')
                                    ->danger()
                                    ->send();

                                return; // Exit the action if the check fails
                            }

                            // Generate URL for asset detail page using assets_number
                            $assetDetailUrl = 'http://127.0.0.1:8000/admin/assets/' . $record->id;

                            // Generate QR code with URL
                            $qrCode = new Builder(
                                writer: new PngWriter(),
                                writerOptions: [],
                                validateResult: false,
                                data: $assetDetailUrl,
                                encoding: new Encoding('UTF-8'),
                                errorCorrectionLevel: ErrorCorrectionLevel::High,
                                size: 100,
                                margin: 5,
                                roundBlockSizeMode: RoundBlockSizeMode::Margin
                            );

                            $result = $qrCode->build();
                            $qrCodeImage = $result->getString();

                            // Generate PDF
                            $pdf = app(DomPDF::class);
                            $pdf->loadView('pdf.asset_label', [
                                'asset' => $record,
                                'qrCodeImage' => base64_encode($qrCodeImage)
                            ]);

                            // Format file name as label-assets_number_name.pdf
                            $fileName = 'label-' . $record->assets_number . '_' . str_replace(' ', '_', $record->name) . '.pdf';

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, $fileName);
                        }),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('primary')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('print_label')
                        ->label('Cetak Label')
                        ->icon('heroicon-o-printer')
                        ->action(function (Collection $records) {
                            // Check if all selected records have a transaction status
                            $missingTransactionStatus = $records->filter(function ($record) {
                                return !$record->transaction_status_id; // Filter out records without transaction status
                            });

                            // If there are records missing the transaction status, show a notification
                            if ($missingTransactionStatus->isNotEmpty()) {
                                Notification::make()
                                    ->title('Kesalahan')
                                    ->body('Beberapa aset yang dipilih tidak memiliki status transaksi. Silakan perbarui sebelum mencetak label.')
                                    ->danger()
                                    ->send();

                                return; // Exit the action if the check fails
                            }

                            // Inisialisasi array untuk QR code
                            $qrCodes = [];

                            // Loop untuk setiap record yang dipilih
                            foreach ($records as $record) {
                                // Generate URL untuk detail aset
                                $assetDetailUrl = 'http://127.0.0.1:8000/admin/assets/' . $record->id;

                                // Generate QR code dengan URL aset
                                $qrCode = new Builder(
                                    writer: new PngWriter(),
                                    writerOptions: [],
                                    validateResult: false,
                                    data: $assetDetailUrl,
                                    encoding: new Encoding('UTF-8'),
                                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                                    size: 100,
                                    margin: 5,
                                    roundBlockSizeMode: RoundBlockSizeMode::Margin
                                );

                                $result = $qrCode->build();

                                // Simpan QR code dalam bentuk string base64
                                $qrCodes[$record->id] = base64_encode($result->getString());
                            }

                            // Generate PDF
                            $pdf = app(DomPDF::class);
                            $pdf->loadView('pdf.asset_label_massal', [
                                'assets' => $records, // Kirim semua record ke Blade
                                'qrCodes' => $qrCodes, // Kirim QR code ke Blade
                            ]);

                            // Format nama file PDF
                            $fileName = 'label-assets-selected.pdf';

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, $fileName);
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $recordId = $page->record->id; // Ambil ID dari aset yang sedang dilihat

        // Ambil item navigasi standar
        $navigationItems = $page->generateNavigationItems([
            ViewAsset::class,
            EditAsset::class,
        ]);

        // Tambahkan item navigasi kustom untuk CreateAssetMonitoring
        $navigationItems[] = NavigationItem::make()
            ->label('Create Asset Monitoring')
            ->url(url('/admin/asset-monitorings/create?assets_id=' . $recordId))
            ->icon('heroicon-o-plus');
        // Tambahkan item navigasi untuk Asset Mutation History
        $navigationItems[] = NavigationItem::make()
            ->label('Riwayat Mutasi Aset')
            ->url(url('/admin/asset-mutations?assets_id=' . $recordId)) // Pastikan ini mengarah ke rute yang benar
            ->icon('heroicon-o-clock');

        return $navigationItems;
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
            'view' => Pages\ViewAsset::route('/{record}'),
        ];
    }
}
