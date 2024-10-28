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
use App\Filament\Resources\AssetPurchaseResource\Pages;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator; // Import Validator
use Illuminate\Validation\ValidationException; // Import ValidationException


class AssetPurchaseResource extends Resource
{
    protected static ?string $model = AssetPurchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Pembelian Barang';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Daftar Pembelian Barang')
                    ->description('Input Daftar Pembelian Barang')
                    ->schema([
                        Forms\Components\Select::make('assetrequest_id')
                            ->options(
                                AssetRequests::query()
                                    ->get()
                                    ->mapWithKeys(function ($assetrequest) {
                                        return [$assetrequest->id => $assetrequest->document_number . ' | ' . $assetrequest->asset_name];
                                    })
                                    ->toArray()
                            )
                            ->afterStateUpdated(function ($set, $state) {
                                $AssetRequest = AssetRequests::find($state);
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
                            ->required()
                            ->validationAttribute('Nomor Permintaan'),
                        Forms\Components\Hidden::make('document_number')
                            ->label('Nomor Permintaan')
                            ->required()
                            ->validationAttribute('Nomor Permintaan'),
                        Forms\Components\TextInput::make('asset_name')
                            ->label('Nama Aset')
                            ->required()
                            ->readOnly()
                            ->reactive()
                            ->validationAttribute('Nama Aset'),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Kategori Barang')
                            ->required()
                            ->disabled()
                            ->reactive()
                            ->validationAttribute('Kategori Barang'),
                        Forms\Components\Hidden::make('category_id')
                            ->label('Kategori Barang')
                            ->required()
                            ->validationAttribute('Kategori Barang'),
                        Forms\Components\TextInput::make('brand')
                            ->label('Merk')
                            ->required()
                            ->maxLength(255)
                            ->validationAttribute('Merk'),
                        Forms\Components\TextInput::make('assets_number')
                            ->label('Nomor Aset')
                            ->required()
                            ->maxLength(255)
                            ->validationAttribute('Nomor Aset'),
                        Forms\Components\DatePicker::make('purchase_date')
                            ->label('Tanggal Pembelian')
                            ->required()
                            ->validationAttribute('Tanggal Pembelian'),
                        Forms\Components\Select::make('condition_id')
                            ->relationship('condition', 'name')
                            ->label('Kondisi Aset')
                            ->required()
                            ->validationAttribute('Kondisi Aset'),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp. ')
                            ->validationAttribute('Harga'),
                        Forms\Components\TextInput::make('funding_source')
                            ->label('Sumber Dana')
                            ->required()
                            ->maxLength(255)
                            ->validationAttribute('Sumber Dana'),
                        Forms\Components\FileUpload::make('payment_receipt')
                            ->label('Bukti Pembelian')
                            ->directory('Asset_Payment_Receipt')
                            ->required()
                            ->validationAttribute('Bukti Pembelian'),
                        Forms\Components\FileUpload::make('img')
                            ->label('Gambar Barang')
                            ->directory('Asset_Purchase')
                            ->validationAttribute('Gambar Barang'),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id())
                            ->validationAttribute('User ID'),
                    ])
            ]);
    }

    public static function validate(Form $form, array $data): array
    {
        $rules = [
            'assetrequest_id' => 'required|exists:asset_requests,id',
            'document_number' => 'required|string|max:255',
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:255',
            'assets_number' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'condition_id' => 'required|exists:master_assets_conditions,id',
            'price' => 'required|numeric|min:0',
            'funding_source' => 'required|string|max:255',
            'payment_receipt' => 'required|mimes:jpeg,png|max:10240',
            'img' => 'nullable|mimes:jpeg,png|max:10240',
            'users_id' => 'required|exists:users,id',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
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
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
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
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition.name')
                    ->label('Kondisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('funding_source')
                    ->label('Sumber Dana')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('payment_receipt')
                    ->label('Bukti Beli')
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
