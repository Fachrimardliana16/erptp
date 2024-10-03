<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Employees;
use Filament\Tables\Table;
use App\Models\AssetPurchase;
use App\Models\AssetRequests;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AssetPurchaseResource\Pages;
use App\Filament\Resources\AssetPurchaseResource\RelationManagers;

class AssetPurchaseResource extends Resource
{
    protected static ?string $model = AssetPurchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Pembelian Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Daftar Pembelian Barang')
                    ->description('Input Daftar pembelian Barang')
                    ->schema([
                        Forms\Components\Select::make('assetrequest_id')
                            ->options(
                                AssetRequests::query()
                                    ->get()
                                    ->mapWithKeys(function ($assetrequest) {
                                        // Menggabungkan 'assets_number' dan 'name' dengan format yang diinginkan
                                        return [$assetrequest->id => $assetrequest->document_number . ' | ' . $assetrequest->asset_name];
                                    })
                                    ->toArray()
                            )
                            ->afterStateUpdated(function ($set, $state) {
                                $AssetRequest = AssetRequests::find($state); // Tambahkan ini untuk debugging
                                if ($AssetRequest) {
                                    $set('document_number', $AssetRequest->document_number);
                                    $set('asset_name', $AssetRequest->asset_name);
                                    $set('category_id', $AssetRequest->category_id);
                                } else {
                                    $set('document_number', null);
                                    $set('asset_name', null);
                                    $set('category_id', null);
                                }
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->label('Nomor Permintaan')
                            ->required(),
                        Forms\Components\Hidden::make('document_number')
                            ->label('Nomor Permintaan')
                            ->required(),
                        Forms\Components\TextInput::make('asset_name')
                            ->label('Nama Aset')
                            ->required()
                            ->readOnly()
                            ->reactive(),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Kategori Barang')
                            ->required()
                            ->reactive(),
                        Forms\Components\Hidden::make('category_id')
                            ->label('Kategori Barang')
                            ->required(),
                        Forms\Components\TextInput::make('brand')
                            ->label('Merk')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('assets_number')
                            ->label('Nomor Aset')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('purchase_date')
                            ->label('Tanggal Pembelian')
                            ->required(),
                        Forms\Components\Select::make('condition_id')
                            ->relationship('condition', 'name')
                            ->label('Kondisi Aset')
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp. '),
                        Forms\Components\TextInput::make('funding_source')
                            ->label('Sumber Dana')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('img')
                            ->label('Gambar')
                            ->directory('Asset_Purchase'),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
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

                        // Render PDF dengan data records dan employee
                        return response()->streamDownload(function () use ($records, $employee) {
                            $pdfContent = Blade::render('pdf.report_asset_purchase', [
                                'records' => $records,
                                'employee' => $employee
                            ]);
                            echo Pdf::loadHTML($pdfContent)
                                ->setPaper('A4', 'landscape') // Set ukuran kertas dan orientasi
                                ->stream();
                        }, 'purchase_assets.pdf');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('img')
                    ->label('Gambar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assetRequest.document_number')
                    ->label('Nomor Permintaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_name')
                    ->label('Nama Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assets_number')
                    ->label('Nomor Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label('Merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_date')
                    ->label('Tanggal Pembelian')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition.name')
                    ->label('Kondisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('Rp. ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('funding_source')
                    ->label('Sumber Dana')
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
            'index' => Pages\ListAssetPurchases::route('/'),
            'create' => Pages\CreateAssetPurchase::route('/create'),
            'edit' => Pages\EditAssetPurchase::route('/{record}/edit'),
        ];
    }
}
