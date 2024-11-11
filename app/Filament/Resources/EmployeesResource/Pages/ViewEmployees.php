<?php

namespace App\Filament\Resources\EmployeesResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Filament\Resources\EmployeesResource;
use Components\SpatieTagsEntry;

use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Tabs;


class ViewEmployees extends ViewRecord
{
    protected static string $resource = EmployeesResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        // Tab Data Pribadi
                        Tabs\Tab::make('Data Pribadi')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                // Profile Image Section
                                Grid::make(3)
                                ->schema([
                                    // Left column: Profile Picture
                                    ImageEntry::make('image')
                                        ->label('Profile Picture')
                                        ->columnSpan(1) // Make the image take one column
                                        ->hiddenLabel(),
                            
                                    // Right column: Text data (Name, Email, etc.)
                                    Grid::make(2)
                                        ->schema([
                                            TextEntry::make('name')
                                            ->label('Nama')
                                            ->icon('heroicon-o-user'),
                                        TextEntry::make('nippam')
                                            ->label('Nippam')
                                            ->icon('heroicon-o-identification'),
                                        TextEntry::make('place_birth')
                                            ->label('Tempat Lahir')
                                            ->icon('heroicon-o-map-pin'),
                                        TextEntry::make('date_birth')
                                            ->label('Tanggal Lahir')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('gender')
                                            ->label('Jenis Kelamin')
                                            ->icon('heroicon-o-user-group'),
                                        TextEntry::make('religion')
                                            ->label('Agama')
                                            ->icon('heroicon-o-bookmark'),
                                        TextEntry::make('age')
                                            ->label('Umur')
                                            ->icon('heroicon-o-clock'),
                                        TextEntry::make('address')
                                            ->label('Alamat')
                                            ->icon('heroicon-o-map'),
                                        TextEntry::make('blood_type')
                                            ->label('Golongan Darah')
                                            ->icon('heroicon-o-beaker'),
                                        TextEntry::make('marital_status')
                                            ->label('Status Menikah')
                                            ->icon('heroicon-o-heart'),
                                            TextEntry::make('phone_number')
                                            ->label('No Telp')
                                            ->icon('heroicon-o-phone'),
                                        TextEntry::make('email')
                                            ->label('Email')
                                            ->columnSpan(2)
                                            ->icon('heroicon-o-envelope'),
                                        ])
                                        ->columnSpan(2), // The text data also takes one column
                                ]),                             
                            ]),
    
                        // Tab Kontak dan Dokumen
                        Tabs\Tab::make('Dokumen')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('id_number')
                                            ->label('Nomor KTP')
                                            ->icon('heroicon-o-document'),
                                        TextEntry::make('familycard_number')
                                            ->label('Nomor KK')
                                            ->icon('heroicon-o-document-text'),
                                        TextEntry::make('npwp_number')
                                            ->label('Nomor NPWP')
                                            ->icon('heroicon-o-currency-dollar'),
                                            TextEntry::make('bank_account_number')
                                            ->label('Nomor Rekening')
                                            ->icon('heroicon-o-banknotes'),
                                        TextEntry::make('bpjs_tk_number')
                                            ->label('Rekening BPJS TK')
                                            ->icon('heroicon-o-shield-check'),
                                        TextEntry::make('bpjs_kes_number')
                                            ->label('Rekening BPJS Kesehatan')
                                            ->icon('heroicon-o-heart'),
                                        TextEntry::make('rek_dplk_pribadi')
                                            ->label('Rekening DPLK Pribadi')
                                            ->icon('heroicon-o-currency-dollar'),
                                        TextEntry::make('rek_dplk_bersama')
                                            ->label('Rekening DPLK Bersama')
                                            ->icon('heroicon-o-currency-dollar'),
                                    ]),
                            ]),   
                        // Tab Pekerjaan
                        Tabs\Tab::make('Pekerjaan')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('entry_date')
                                            ->label('Tanggal Masuk')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('probation_appointment_date')
                                            ->label('Tanggal Calon Pegawai')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('length_service')
                                            ->label('Masa Kerja')
                                            ->icon('heroicon-o-clock'),
                                        TextEntry::make('retirement')
                                            ->label('Tahun Pensiun')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('employmentStatus.name')
                                            ->label('Status')
                                            ->icon('heroicon-o-check-badge'),
                                        TextEntry::make('employeeAgreement.name')
                                            ->label('Kontrak Kerja')
                                            ->icon('heroicon-o-document'),
                                        TextEntry::make('agreement_date_start')
                                            ->label('Tanggal Mulai Perjanjian Kontrak')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('agreement_date_end')
                                            ->label('Tanggal Akhir Perjanjian Kontrak')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('employeeGrade.name')
                                            ->label('Golongan')
                                            ->icon('heroicon-o-chart-bar'),
                                        TextEntry::make('grade_date_start')
                                            ->label('Tanggal Mulai Golongan')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('grade_date_end')
                                            ->label('Tanggal Akhir Golongan')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('employeePosition.name')
                                            ->label('Jabatan')
                                            ->icon('heroicon-o-briefcase'),
                                        TextEntry::make('employeeDepartments.name')
                                            ->label('Bagian')
                                            ->icon('heroicon-o-building-office'),
                                        TextEntry::make('employeesubDepartments.name')
                                            ->label('Sub Bagian')
                                            ->icon('heroicon-o-building-office'),
                                    ]),
                            ]),
    
                        // Tab Gaji
                        Tabs\Tab::make('Gaji')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('basic_salary')
                                            ->label('Gaji Pokok')
                                            ->money('Rp. {value}')
                                            ->columnSpan(2)
                                            ->icon('heroicon-o-currency-dollar'),
                                        TextEntry::make('periodic_salary_date_start')
                                            ->label('Tanggal Mulai Berkala')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('periodic_salary_date_end')
                                            ->label('Tanggal Akhir Berkala')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('grade_date_start')
                                            ->label('Tanggal Mulai Golongan')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('grade_date_end')
                                            ->label('Tanggal Akhir Golongan')
                                            ->icon('heroicon-o-calendar'),
                                        TextEntry::make('amount')
                                            ->label('Berkala')
                                            ->money('Rp. {value}')
                                            ->columnSpan(2)
                                            ->icon('heroicon-o-currency-dollar'),
                                    ]),
                            ]),
                    ])->columnSpan(2),
            ]);
    }   
}