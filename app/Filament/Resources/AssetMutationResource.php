<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Asset;
use Filament\Forms\Form;
use App\Models\Employees;
use Filament\Tables\Table;
use App\Models\AssetMutation;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Barryvdh\DomPDF\PDF as DomPDF;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;

// library untuk export PDF
use App\Filament\Resources\AssetMutationResource\Pages;

class AssetMutationResource extends Resource
{
    protected static ?string $model = AssetMutation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Mutasi Aset';
    public static $order = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Mutasi Aset')
                    ->description('Form input mutasi aset')
                    ->schema([
                        Forms\Components\TextInput::make('mutations_number')
                            ->label('Nomor Mutasi Aset')
                            ->required(),
                        Forms\Components\DatePicker::make('mutation_date')
                            ->label('Tanggal Mutasi')
                            ->required(),
                        Forms\Components\Select::make('transaction_status_id')
                            ->relationship('AssetsMutationtransactionStatus', 'name')
                            ->label('Status Mutasi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('assets_id')
                            ->options(
                                Asset::query()
                                    ->get()
                                    ->mapWithKeys(function ($assets) {
                                        // Menggabungkan 'assets_number' dan 'name' dengan format yang diinginkan
                                        return [$assets->id => $assets->assets_number . ' | ' . $assets->name];
                                    })
                                    ->toArray()
                            )
                            ->afterStateUpdated(function ($set, $state) {
                                $aset = Asset::find($state);
                                $set('assets_number', $aset->assets_number);
                                $set('name', $aset->name);
                                $set('condition_id', $aset->condition_id);
                            })
                            ->label('Nomor Aset')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Hidden::make('assets_number')
                            ->default(function ($get) {
                                $assets_id = $get('assets_id');
                                $asset = Asset::find($assets_id);
                                return $asset ? $asset->assets_number : null;
                            }),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Aset')
                            ->required()
                            ->readonly(),
                        Forms\Components\Select::make('condition_id')
                            ->relationship('MutationCondition', 'name')
                            ->label('Kondisi')
                            ->required()
                            ->disabled(),
                        Forms\Components\Hidden::make('condition_id')
                            ->default(function ($get) {
                                return $get('condition_id');
                            }),
                        Forms\Components\Select::make('employees_id')
                            ->relationship('AssetsMutationemployee', 'name')
                            ->label('Pemegang')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('location_id')
                            ->relationship('AssetsMutationlocation', 'name')
                            ->label('Lokasi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('sub_location_id')
                            ->relationship('AssetsMutationsubLocation', 'name')
                            ->label('Sub Lokasi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\FileUpload::make('scan_doc')
                            ->directory('Mutation Assets')
                            ->label('Scan Dokumen'),
                        Forms\Components\Textarea::make('desc')
                            ->label('Keterangan')
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
                        $pdfContent = Blade::render('pdf.report_asset_mutation', [
                            'records' => $records,
                            'employee' => $employee
                        ]);
                        echo Pdf::loadHTML($pdfContent)->stream();
                    }, 'mutation_assets.pdf');
                }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mutation_date')
                    ->label('Tanggal Mutasi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('AssetsMutationtransactionStatus.name')
                    ->label('Status Transaksi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetsMutation.assets_number')
                    ->label('Nomor Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetsMutation.name')
                    ->label('Nama Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MutationCondition.name')
                    ->label('Kondisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetsMutationemployee.name')
                    ->label('Pemegang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetsMutationlocation.name')
                    ->label('Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('AssetsMutationsubLocation.name')
                    ->label('Sub Lokasi')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('scan_doc')
                    ->label('Scan Dokumen')
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
                // Action untuk download pdf aset per record
                Action::make('download_pdf')
                ->label('Cetak Surat Mutasi')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function($record){
                    $pdf = app (abstract: DomPDF::class);
                    $pdf->loadView('pdf.surat_mutasi_asset', ['assetmutation'=> $record]);

                    return response()->streamDownload(function() use($pdf){
                        echo $pdf->output();
                    }, 'mutasi_asset-'.$record->name.'.pdf');
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
            'index' => Pages\ListAssetMutations::route('/'),
            'create' => Pages\CreateAssetMutation::route('/create'),
            'edit' => Pages\EditAssetMutation::route('/{record}/edit'),
        ];
    }
}
