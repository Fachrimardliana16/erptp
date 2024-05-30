<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetDisposalResource\Pages;
use App\Filament\Resources\AssetDisposalResource\RelationManagers;
use App\Models\AssetDisposal;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetDisposalResource extends Resource
{
    protected static ?string $model = AssetDisposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Kerumah Tanggaan';
    protected static ?string $navigationLabel = 'Penghapusan Aset';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Penghapusan Aset')
                    ->description('Input data penghapusan aset')
                    ->schema([
                        Forms\Components\TextInput::make('disposals_number')
                            ->label('Nomor Penghapusan Aset')
                            ->required(),
                        Forms\Components\DatePicker::make('disposal_date')
                            ->label('Tanggal Penghapusan')
                            ->required(),
                        Forms\Components\Select::make('assets_id')
                            ->relationship('assetDisposals', 'name')
                            ->label('Nama Aset')
                            ->required(),
                        Forms\Components\TextInput::make('book_value')
                            ->label('Nilai Buku')
                            ->required()
                            ->prefix('Rp. ')
                            ->numeric(),
                        Forms\Components\Textarea::make('disposal_reason')
                            ->label('Alasan Penghapusan')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('disposal_value')
                            ->label('Nilai Penghapusan')
                            ->required()
                            ->prefix('Rp. ')
                            ->numeric(),
                        Forms\Components\Select::make('disposal_process')
                            ->label('Proses Penghapusan')
                            ->options([
                                'dihapus secara fisik' => 'Dihapus Secara Fisik',
                                'dihapus dari daftar inventaris' => 'Dihapus Dari Daftar Inventaris',
                                'dijual' => 'Dijual',
                            ])
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employeeDisposals', 'name')
                            ->label('Pejabat Mengetahui')
                            ->required(),
                        Forms\Components\Textarea::make('disposal_notes')
                            ->label('Catatan Penghapusan')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('docs')
                            ->directory('Disposals')
                            ->label('Lampiran Surat Keputusan'),
                        Forms\Components\Hidden::make('users_id')
                            ->default(auth()->id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('disposal_date')
                    ->label('Tanggal Penghapusan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assetDisposals.name')
                    ->label('Nama Aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('book_value')
                    ->label('Nilai Buku')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disposal_reason')
                    ->label(' Alasan Penghapusan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disposal_value')
                    ->label('Nilai Penghapusan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disposal_process')
                    ->label('Proses Penghapusan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employeeDisposals.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('disposal_notes')
                    ->label('Catatan Penghapusan')
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
            'index' => Pages\ListAssetDisposals::route('/'),
            'create' => Pages\CreateAssetDisposal::route('/create'),
            'edit' => Pages\EditAssetDisposal::route('/{record}/edit'),
        ];
    }
}
