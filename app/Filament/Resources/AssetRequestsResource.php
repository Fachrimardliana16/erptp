<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Employees;
use Filament\Tables\Table;
use App\Models\AssetRequests;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AssetRequestsResource\Pages;
use App\Filament\Resources\AssetRequestsResource\RelationManagers;
use Carbon\Carbon;

class AssetRequestsResource extends Resource
{
    protected static ?string $model = AssetRequests::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Permintaan Barang';
    public static $order = 1;

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
                            ->validationAttribute('Nomor Dokumen'),
                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Permintaan')
                            ->default(now())
                            ->required()
                            ->validationAttribute('Tanggal Permintaan'),
                        Forms\Components\TextInput::make('asset_name')
                            ->label('Nama Barang')
                            ->required()
                            ->maxLength(255)
                            ->validationAttribute('Nama Barang'),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Kategori Barang')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->validationAttribute('Kategori Barang'),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah Satuan')
                            ->required()
                            ->placeholder('buah/pack/set/dll')
                            ->numeric()
                            ->validationAttribute('Jumlah Satuan'),
                        Forms\Components\TextInput::make('purpose')
                            ->label('Untuk Keperluan')
                            ->required()
                            ->maxLength(255)
                            ->validationAttribute('Untuk Keperluan'),
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
                            ->helperText('Foto atau scan dengan format ".jpeg atau .png".')
                            ->label('Bukti Lampiran')
                            ->directory('Assets_Request')
                            ->columnSpanFull()
                            ->required()
                            ->validationAttribute('Bukti Lampiran'),
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

                        // Render PDF dengan data records dan employee
                        return response()->streamDownload(function () use ($records, $employee) {
                            $pdfContent = Blade::render('pdf.report_asset_request', [
                                'records' => $records,
                                'employee' => $employee
                            ]);
                            echo Pdf::loadHTML($pdfContent)->stream();
                        }, 'request_assets.pdf');
                    }),
            ])
            ->columns([
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
