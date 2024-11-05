<?php

namespace App\Filament\Resources\EmployeesResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Forms\Components\Select;
use App\Models\Employees;


class EmployeesAgreementOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    public $statusFilter = '';

    public function mount()
    {
        $this->statusFilter = request()->get('statusFilter', '');
    }

    protected function getFilters(): array
    {
        return [
            Select::make('statusFilter')
                ->label('Filter by Status')
                ->options([
                    '' => 'All',
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ])
                ->default(''),
        ];
    }

    protected function getStats(): array
    {
        $query = Employees::query();

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        $totalEmployees = $query->count();
        $pkwtCount = $query->where('master_employee_agreement_id', function ($query) {
            $query->select('id')->from('master_employee_agreement')->where('name', '[PKWT] Perjanjian Kerja Waktu Tertentu');
        })->count();
        $pkwttCount = $query->where('master_employee_agreement_id', function ($query) {
            $query->select('id')->from('master_employee_agreement')->where('name', '[PKWTT] Perjanjian Kerja Waktu Tidak Tertentu');
        })->count();

        return [
            Stat::make('Total Employees', $totalEmployees),
            Stat::make('[PKWT] Perjanjian Kerja Waktu Tertentu', $pkwtCount),
            Stat::make('[PKWTT] Perjanjian Kerja Waktu Tidak Tertentu', $pkwttCount),
        ];
    }
}
