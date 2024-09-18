<?php

namespace App\Filament\Resources\AssetResource\Pages;

use Filament\Actions;
use Components\SpatieTagsEntry;
use Filament\Infolists\Infolist;

use Filament\Resources\Pages\Page;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Split;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\AssetResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class ViewAsset extends ViewRecord
{
    protected static string $resource = AssetResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detail Aset')
                    ->schema([
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    Group::make([
                                        TextEntry::make('name')
                                            ->label('Nama Aset'),
                                        TextEntry::make('assets_number')
                                            ->label('Nomor Aset'),
                                        TextEntry::make('purchase_date')
                                            ->label('Tanggal Pembelian')
                                            ->badge()
                                            ->date()
                                            ->color('success'),
                                    ]),
                                    Group::make([
                                        TextEntry::make('categoryAsset.name')
                                            ->label('Kategori Aset'),
                                        TextEntry::make('conditionAsset.name')
                                            ->label('Kondisi Aset'),
                                        TextEntry::make('price')
                                            ->label('Harga')
                                            ->money('Rp. '),
                                    ]),
                                    Group::make([
                                        TextEntry::make('funding_source')
                                            ->label('Sumber Dana'),
                                        TextEntry::make('brand')
                                            ->label('Merk'),
                                        TextEntry::make('assetsStatus.name')
                                            ->label('Status Aset'),
                                    ]),
                                    Group::make([
                                        TextEntry::make('assetTransactionStatus.name')
                                            ->label('Status Transaksi Aset'),
                                        TextEntry::make('book_value_expiry')
                                            ->label('Nilai Buku Kadaluarsa')
                                            ->badge()
                                            ->date()
                                            ->color('danger')
                                    ]),
                                ]),
                            ImageEntry::make('img')
                                ->hiddenLabel()
                                ->grow(false),
                        ])->from('lg'),
                    ])
            ]);
    }
}