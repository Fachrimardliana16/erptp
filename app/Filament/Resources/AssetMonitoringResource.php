<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetMonitoringResource\Pages;
use App\Models\Asset;
use App\Models\AssetMonitoring;
use App\Models\AssetMutation;
use App\Models\Employees;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;

class AssetMonitoringResource extends Resource
{
    protected static ?string $model = AssetMonitoring::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationGroup = 'Asset';
    protected static ?string $navigationLabel = 'Monitoring Aset';
    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Monitoring Aset')
                    ->description('Input data monitoring aset')
                    ->schema([
                        Forms\Components\DatePicker::make('monitoring_date')
                            ->label('Tanggal Monitoring')
                            ->default(now())
                            ->required()
                            ->validationAttribute('Tanggal Monitoring'),
                        Forms\Components\Select::make('assets_id')
                            ->options(
                                Asset::query()
                                    ->get()
                                    ->mapWithKeys(function ($asset) {
                                        return [$asset->id => $asset->assets_number . ' | ' . $asset->name];
                                    })
                                    ->toArray()
                            )
                            ->afterStateUpdated(function ($set, $state) {
                                $aset = Asset::find($state);
                                if ($aset) {
                                    $set('assets_number', $aset->assets_number);
                                    $set('name', $aset->name);
                                    $set('old_condition_id', $aset->condition_id);
                                }
                            })
                            ->label('Nomor Aset')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->validationAttribute('Nomor Aset'),
                        Forms\Components\Hidden::make('assets_number')
                            ->default(function ($get) {
                                $assets_id = $get('assets_id');
                                $asset = AssetMutation::find($assets_id);
                                return $asset ? $asset->assets_number : null;
                            }),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Aset')
                            ->required()
                            ->readOnly()
                            ->validationAttribute('Nama Aset'),
                        Forms\Components\Select::make('old_condition_id')
                            ->relationship('MonitoringoldCondition', 'name')
                            ->label('Kondisi Lama')
                            ->required()
                            ->disabled()
                            ->validationAttribute('Kondisi Lama'),
                        Forms\Components\Hidden::make('old_condition_id')
                            ->default(function ($get) {
                                return $get('old_condition_id');
                            }),
                        Forms\Components\Select::make('new_condition_id')
                            ->relationship('MonitoringNewCondition', 'name')
                            ->label('Kondisi Baru')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->validationAttribute('Kondisi Baru'),
                        Forms\Components\FileUpload::make('img')
                            ->directory('Asset_Monitoring')
                            ->label('Foto Upload'),
                        Forms\Components\Textarea::make('desc')
                            ->label('Keterangan')
                            ->columnSpanFull()
                            ->validationAttribute('Keterangan'),
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
                            $pdfContent = Blade::render('pdf.report_asset_monitoring', [
                                'records' => $records,
                                'employee' => $employee
                            ]);
                            echo Pdf::loadHTML($pdfContent)->stream();
                        }, 'monitoring_assets.pdf');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('monitoring_date')
                    ->label('Tanggal Monitoring')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('assetMonitoring.assets_number')
                    ->label('Nomor Aset')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('assetMonitoring.name')
                    ->label('Nama Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MonitoringoldCondition.name')
                    ->label('Kondisi Lama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MonitoringNewCondition.name')
                    ->label('Kondisi Baru')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('img')
                    ->label('Foto'),
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
            'index' => Pages\ListAssetMonitorings::route('/'),
            'create' => Pages\CreateAssetMonitoring::route('/create'),
            'edit' => Pages\EditAssetMonitoring::route('/{record}/edit'),
        ];
    }
}
