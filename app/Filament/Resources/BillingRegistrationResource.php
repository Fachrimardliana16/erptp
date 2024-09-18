<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillingRegistrationResource\Pages;
use App\Filament\Resources\BillingRegistrationResource\RelationManagers;
use App\Models\BillingRegistration;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;

class BillingRegistrationResource extends Resource
{
    protected static ?string $model = BillingRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Billing';
    protected static ?string $navigationLabel = 'Pendaftaran/Registrasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Registrasi/Pendaftaran Calon Pelanggan')
                    ->schema([
                        Forms\Components\Tabs::make('')
                            ->tabs([
                                Tabs\Tab::make('Profile')
                                    ->schema([
                                        DatePicker::make('date')
                                            ->label('Tanggal Pendaftaran')
                                            ->required(),
                                        TextInput::make('name')
                                            ->label('Nama Calon Pelanggan')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('sub_district_id')
                                            ->relationship('subDistrict', 'name')
                                            ->label('Kecamatan')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Select::make('village_id')
                                            ->relationship('village', 'name')
                                            ->label('Kelurahan')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        TextInput::make('detail_address')
                                            ->label('Detail Alamat')
                                            ->required(),
                                        Select::make('id_type')
                                            ->label('Kartu Identitas')
                                            ->relationship('idType', 'name')
                                            ->required(),
                                        TextInput::make('id_number')
                                            ->label('Nomor Identitas')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('number_phone')
                                            ->label('Nomor Telepon')
                                            ->tel()
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label('E-Mail')
                                            ->email()
                                            ->maxLength(255),
                                        Select::make('work_name')
                                            ->options([
                                                'belum bekerja' => 'Belum Bekerja',
                                                'pelajar/mahasiswa' => 'Pelajar/Mahasiswa',
                                                'pegawai negeri sipil' => 'Pegawai Negeri Sipil (PNS)',
                                                'pegewai bumd' => 'Pegawai BUMD',
                                                'pegawai bumn' => 'Pegawai BUMN',
                                                'tni' => 'TNI',
                                                'polisi' => 'Polisi',
                                                'pegawai swasta' => 'Pegawai Swasta',
                                                'wirausaha' => 'Wirausaha',
                                            ])
                                            ->label('Pekerjaan'),
                                    ])->columns(2),
                                Tabs\Tab::make('Pendaftaran')
                                    ->schema([
                                        Select::make('registration_type_id')
                                            ->relationship('registrationType', 'name')
                                            ->label('Tipe Registrasi/Pendaftaran')
                                            ->required(),
                                        TextInput::make('number')
                                            ->label('Nomor Langganan')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('branch_office_id')
                                            ->relationship('branchOffice', 'name')
                                            ->label('Kantor Cabang')
                                            ->required(),
                                        TextInput::make('family_home')
                                            ->label('Luas Tanah')
                                            ->suffix('m2')
                                            ->required(),
                                        TextInput::make('surface_area')
                                            ->label('Luas Bangunan')
                                            ->suffix('m2')
                                            ->numeric(),
                                        Select::make('floor_type_id')
                                            ->relationship('floorType', 'name')
                                            ->label('Jenis Lantai'),
                                        Select::make('roof_type_id')
                                            ->relationship('roofType', 'name')
                                            ->label('Jenis Atap'),
                                        Select::make('vehicle_type_id')
                                            ->relationship('vehicleType', 'name')
                                            ->label('Jenis Kepemilikan Kendaraan'),
                                        TextInput::make('building_area')
                                            ->label('Jenis Bangunan'),
                                        Select::make('wall_type_id')
                                            ->relationship('wallType', 'name')
                                            ->label('Jenis Dinding'),
                                        TextInput::make('nominal_payment')
                                            ->label('Biaya Pendaftaran')
                                            ->prefix('Rp. ')
                                            ->columnspanfull()
                                            ->numeric(),
                                        Toggle::make('need_to_survey')
                                            ->label('Kebutuhan Survey'),
                                        Toggle::make('fast_installation')
                                            ->label('Kebutuhan Pasangan Kilat')
                                            ->required(),
                                        FileUpload::make('spl_image')
                                            ->label('Arsip Surat Permintaan Pelanggan')
                                            ->image(),
                                        FileUpload::make('agreement_image')
                                            ->label('Arsip Surat Kontrak Pelanggan')
                                            ->image(),
                                        TextArea::make('desc')
                                            ->label('Catatan')
                                            ->columnSpanFull(),
                                    ])->columns(2),
                                Tabs\Tab::make('GeoLokasi')
                                    ->schema([
                                        TextInput::make('altitude')
                                            ->label('Altitude')
                                            ->numeric(),
                                        TextInput::make('latitude')
                                            ->label('Latitude')
                                            ->numeric(),
                                        TextInput::make('longitude')
                                            ->label('Longitude')
                                            ->numeric(),
                                        Hidden::make('users_id')
                                            ->default(auth()->id()),
                                    ])->columns(1),
                            ])
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal Registrasi/Pendaftaran')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Calon Pelanggan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('detail_address')
                    ->label('Detail Alamat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('village.name')
                    ->label('Kelurahan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subDistrict.name')
                    ->label('Kecamatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('idType.name')
                    ->label('Kartu Identitas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_number')
                    ->label('Nomor Identitas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_phone')
                    ->label('No Telp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('work_name')
                    ->label('Pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registrationType.name')
                    ->label('Tipe Registrasi/Pendaftaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('Nomor Langganan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('branchOffice.name')
                    ->label('Kantor Cabang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('family_home')
                    ->label('Luas Tanah')
                    ->suffix('m2')
                    ->numeric(),
                Tables\Columns\TextColumn::make('surface_area')
                    ->label('Luas Bangunan')
                    ->numeric()
                    ->suffix('m2')
                    ->sortable(),
                Tables\Columns\TextColumn::make('floorType.name')
                    ->label('Tipe Lantai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roofType.name')
                    ->label('Tipe Plafon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicleType.name')
                    ->label('Kepemilikan Kendaraan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('building_area')
                    ->label('Tipe Bangunan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('wallType.name')
                    ->label('Tipe Dinding')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal_payment')
                    ->label('Total Pembayaran')
                    ->money('Rp. ')
                    ->sortable(),
                Tables\Columns\IconColumn::make('need_to_survey')
                    ->label('Kebutuhan Survey')
                    ->boolean(),
                Tables\Columns\IconColumn::make('fast_installation')
                    ->label('Kebutuhan Pasang Kilat')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('spl_image')
                    ->label('Arsip SPL'),
                Tables\Columns\ImageColumn::make('agreement_image')
                    ->label('Arsip Kontrak Pelanggan'),
                Tables\Columns\TextColumn::make('altitude')
                    ->label('Alti')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->label('Lati')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->label('Longi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('desc')
                    ->label('Catatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('users_id')
                    ->searchable()
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
            'index' => Pages\ListBillingRegistrations::route('/'),
            'create' => Pages\CreateBillingRegistration::route('/create'),
            'edit' => Pages\EditBillingRegistration::route('/{record}/edit'),
        ];
    }
}
