<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetRequestsResource\Pages;
use App\Filament\Resources\AssetRequestsResource\RelationManagers;
use App\Models\AssetRequests;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;

class AssetRequestsResource extends Resource
{
    protected static ?string $model = AssetRequests::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Permintaan Barang';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Daftar Permintaan Kebutuhan Barang')
                    ->description('Input Daftar Permintaan Kebutuhan Barang')
                    ->schema([
                        Forms\Components\TextInput::make('document_number')
                            ->label('Nomor Dokumen')
                            ->required()
                            ->placeholder('Exp: XX/nama_sub_bagian/MM/YYYY.')
                            ->maxLength(255)
                            ->validationAttribute('Nomor Dokumen')
                            ->rules('required|string|max:255'),
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Permintaan')
                            ->default(now())
                            ->required()
                            ->validationAttribute('Tanggal Permintaan')
                            ->rules('required|date'),
                        Forms\Components\TextInput::make('asset_name')
                            ->label('Nama Barang')
                            ->required()
                            ->maxLength(255)
                            ->validationAttribute('Nama Barang')
                            ->rules('required|string|max:255'),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Kategori Barang')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->validationAttribute('Kategori Barang')
                            ->rules('required|exists:master_assets_category,id'),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah Satuan')
                            ->required()
                            ->numeric()
                            ->validationAttribute('Jumlah Satuan')
                            ->rules('required|numeric|min:1'),
                        Forms\Components\TextInput::make('purpose')
                            ->label('Untuk Keperluan')
                            ->required()
                            ->maxLength(255)
                            ->validationAttribute('Untuk Keperluan')
                            ->rules('required|string|max:255'),
                        Forms\Components\Textarea::make('desc')
                            ->label('Keterangan')
                            ->columnSpanFull()
                            ->validationAttribute('Keterangan'),
                    ]),
                Section::make('Pengesahan')
                    ->description('Pengesahan Permintaan')
                    ->schema([
                        Forms\Components\Toggle::make('kepala_sub_bagian')
                            ->label('Kepala Sub Bagian')
                            ->validationAttribute('Kepala Sub Bagian'),
                        Forms\Components\Toggle::make('kepala_bagian_umum')
                            ->label('Kepala Bagian Umum')
                            ->validationAttribute('Kepala Bagian Umum'),
                        Forms\Components\Toggle::make('kepala_bagian_keuangan')
                            ->label('Kepala Bagian Keuangan')
                            ->validationAttribute('Kepala Bagian Keuangan'),
                        Forms\Components\Toggle::make('direktur_umum')
                            ->label('Direktur Umum')
                            ->validationAttribute('Direktur Umum'),
                        Forms\Components\Toggle::make('direktur_utama')
                            ->label('Direktur Utama')
                            ->validationAttribute('Direktur Utama'),
                        Forms\Components\FileUpload::make('docs')
                            ->helperText('Foto atau scan dengan format ".jpeg atau . png".')
                            ->label('Bukti Lampiran')
                            ->directory('Assets_Request')
                            ->columnSpanFull()
                            ->required()
                            ->validationAttribute('Bukti Lampiran')
                            ->rules('required|mimes:jpeg,png|max:5024'),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id())
                            ->validationAttribute('User ID'),
                    ])->columns(3)
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
                            $pdfContent = Blade::render('pdf.report_asset_request', [
                                'records' => $records,
                                'employee' => $employee
                            ]);
                            echo Pdf::loadHTML($pdfContent)
                                ->setPaper('A4', 'landscape')
                                ->stream();
                        }, 'request_assets.pdf');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status_request')
                    ->label('Status Permintaan')
                    ->boolean(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Nomor Dokumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('asset_name')
                    ->label('Nama Barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Jenis Barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah Barang')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->label('Keperluan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('desc')
                    ->label('Keterangan')
                    ->searchable(),
                Tables\Columns\IconColumn::make('kepala_sub_bagian')
                    ->label('Kepala Sub Bagian')
                    ->boolean(),
                Tables\Columns\IconColumn::make('kepala_bagian_umum')
                    ->label('Kepala Bagian Umum')
                    ->boolean(),
                Tables\Columns\IconColumn::make('kepala_bagian_keuangan')
                    ->label('Kepala Bagian Keuangan')
                    ->boolean(),
                Tables\Columns\IconColumn::make('direktur_umum')
                    ->label('Direktur Umum')
                    ->boolean(),
                Tables\Columns\IconColumn::make('direktur_utama')
                    ->label('Direktur Utama')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('docs')
                    ->label('Dokumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListAssetRequests::route('/'),
            'create' => Pages\CreateAssetRequests::route('/create'),
            'edit' => Pages\EditAssetRequests::route('/{record}/edit'),
        ];
    }
}
