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
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Endroid\QrCode\ErrorCorrectionLevel;
// use Illuminate\Database\Eloquent\Builder;

// library untuk export PDF
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;

// library endroid qr code
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\Pages\EditAsset;
use App\Filament\Resources\AssetResource\Pages\ViewAsset;
use App\Filament\Resources\AssetMonitoringResource\Pages\CreateAssetMonitoring;


class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Data Aset';
    public static $order = 2;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

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
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Aset')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('category_id')
                            ->relationship('categoryAsset', 'name')
                            ->label('Kategori')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('status_id')
                            ->relationship('assetsStatus', 'name')
                            ->label('Status')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('transaction_status_id')
                            ->relationship('AssetTransactionStatus', 'name')
                            ->label('Status Transaksi')
                            ->hidden(),
                        Forms\Components\DatePicker::make('purchase_date')
                            ->label('Tanggal Pembelian')
                            ->required()
                            ->default(Carbon::today()),
                        Forms\Components\Select::make('condition_id')
                            ->relationship('conditionAsset', 'name')
                            ->label('Kondisi')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Beli')
                            ->required()
                            ->numeric()
                            ->prefix('Rp. '),
                        Forms\Components\TextInput::make('funding_source')
                            ->label('Sumber Dana')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('brand')
                            ->label('Merk')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('book_value')
                            ->label('Nilai Buku')
                            ->required()
                            ->maxLength(255)
                            ->prefix('Rp. '),
                        Forms\Components\DatePicker::make('book_value_expiry')
                            ->label('Tanggal Habis Buku')
                            ->required(),
                        Forms\Components\Textarea::make('desc')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('img')
                            ->directory('Assets')
                            ->label('Gambar'),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                // BulkAction untuk cetak label, diletakkan di sebelah kiri
                Tables\Actions\BulkAction::make('print_label')
                    ->label('Cetak Label')
                    ->icon('heroicon-o-printer')
                    ->action(function (Collection $records) {
                        // Inisialisasi array untuk QR code
                        $qrCodes = [];

                        // Loop untuk setiap record yang dipilih
                        foreach ($records as $record) {
                            // Generate URL untuk detail aset
                            $assetDetailUrl = 'http://127.0.0.1:8000/admin/assets/' . $record->id;

                            // Generate QR code dengan URL aset
                            $qrCode = Builder::create()
                                ->writer(new PngWriter())
                                ->writerOptions([])
                                ->data($assetDetailUrl)
                                ->encoding(new Encoding('UTF-8'))
                                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                                ->size(100)
                                ->margin(5)
                                ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
                                ->build();

                            // Simpan QR code dalam bentuk string base64
                            $qrCodes[$record->id] = base64_encode($qrCode->getString());
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

                // BulkAction untuk export PDF (sudah ada sebelumnya)
                Tables\Actions\BulkAction::make('Export Pdf') // Action untuk download PDF yang sudah difilter
                    ->icon('heroicon-m-arrow-down-tray')
                    ->deselectRecordsAfterCompletion()
                    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                        // Ambil data karyawan yang memiliki jabatan 'Kepala Sub Bagian Kerumahtanggaan'
                        $employee = Employees::whereHas('employeePosition', function ($query) {
                            $query->where('name', 'Kepala Sub Bagian Kerumahtanggaan');
                        })->first();

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
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conditionAsset.name')
                    ->label('Kondisi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->sortable()
                    ->money('Rp. ')
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
                    ->date()
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
                            }, 'asset-' . $record->name . '.pdf');
                        }),

                    Action::make('print_label')
                        ->label('Cetak Label')
                        ->icon('heroicon-o-printer')
                        ->action(function ($record) {
                            // Generate URL for asset detail page using assets_number
                            $assetDetailUrl = 'http://127.0.0.1:8000/admin/assets/' . $record->id;

                            // Generate QR code with URL
                            $qrCode = Builder::create()
                                ->writer(new PngWriter())
                                ->writerOptions([])
                                ->data($assetDetailUrl) // Use the URL here
                                ->encoding(new Encoding('UTF-8'))
                                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                                ->size(100)
                                ->margin(5)
                                ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
                                ->build();

                            $qrCodeImage = $qrCode->getString();

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
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewAsset::class,
            EditAsset::class,
            CreateAssetMonitoring::class,
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
            'view' => Pages\ViewAsset::route('/{record}'),
        ];
    }
}
