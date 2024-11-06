<?php

namespace App\Filament\Resources\EmployeeAgreementResource\Pages;

use App\Filament\Resources\EmployeeAgreementResource;
use App\Filament\Resources\EmployeeAgreementResource\Widgets\AgreementOverview;
use App\Models\EmployeeAgreement;
use App\Models\MasterEmployeeAgreement;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEmployeeAgreements extends ListRecords
{
    protected static string $resource = EmployeeAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AgreementOverview::class,
        ];
    }

    public function getTabs(): array
    {
        // Ambil semua data agreement dari tabel master_employee_agreement
        $agreements = MasterEmployeeAgreement::pluck('id', 'name')->toArray();

        // Inisialisasi array tabs dengan entri default 'Semua'
        $tabs = [
            'all' => Tab::make('Semua')
                ->badge(EmployeeAgreement::count()),
        ];

        // Tambahkan entri dinamis untuk setiap agreement dengan badge
        foreach ($agreements as $name => $id) {
            $tabs[str($name)->slug()->toString()] = Tab::make($name)
                ->modifyQueryUsing(fn(Builder $query) => $query->where('agreement_id', $id))
                ->badge(EmployeeAgreement::where('agreement_id', $id)->count());
        }

        return $tabs;
    }
}
