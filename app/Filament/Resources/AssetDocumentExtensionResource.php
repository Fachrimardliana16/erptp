<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetDocumentExtensionResource\Pages;
use App\Filament\Resources\AssetDocumentExtensionResource\RelationManagers;
use App\Models\Asset;
use App\Models\AssetDocumentExtension;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;

class AssetDocumentExtensionResource extends Resource
{
    protected static ?string $model = AssetDocumentExtension::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Perpanjangan Aset';
    protected static ?int $navigationSort = 7;
    protected static bool $shouldRegisterNavigation = false;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Perpanjangan Aset')
                    ->description('Input data perpanjangan aset')
                    ->schema([
                        Forms\Components\DatePicker::make('extension_date')
                            ->label('Tanggal Perpanjangan')
                            ->required(),
                        Forms\Components\Select::make('document_type')
                            ->label('Tipe Dokumen')
                            ->required()
                            ->options([
                                'bpkb' => 'BPKB',
                                'stnk' => 'STNK',
                                'lain' => 'Lain-Lain',
                            ]),
                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('assets_id')
                            ->options(
                                Asset::query()
                                    ->get()
                                    ->mapWithKeys(function ($asset) {
                                        // Menggabungkan 'assets_number' dan 'name' dengan format yang diinginkan
                                        return [$asset->id => $asset->assets_number . ' | ' . $asset->name];
                                    })
                                    ->toArray()
                            )
                            ->label('Nama Aset')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('cost')
                            ->label('Biaya')
                            ->required()
                            ->numeric()
                            ->prefix('Rp. '),
                        Forms\Components\DatePicker::make('next_expiry_date')
                            ->label('Perpanjangan Berikutnya')
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
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
                            $pdfContent = Blade::render('pdf.report_asset_document_extension', [
                                'records' => $records,
                                'employee' => $employee
                            ]);
                            echo Pdf::loadHTML($pdfContent)->stream();
                        }, 'document_extension_assets.pdf');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('extension_date')
                    ->label('Tanggal Perpanjangan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_type')
                    ->label('Jenis Perpanjangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetDocumentExtension.name')
                    ->label('Nama Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Biaya')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('next_expiry_date')
                    ->label('Perpanjangan Berikutnya')
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
            'index' => Pages\ListAssetDocumentExtensions::route('/'),
            'create' => Pages\CreateAssetDocumentExtension::route('/create'),
            'edit' => Pages\EditAssetDocumentExtension::route('/{record}/edit'),
        ];
    }
}
