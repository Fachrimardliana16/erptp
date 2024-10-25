<?php

namespace App\Filament\Resources\EmployeeJobApplicationArchivesResource\Widgets;

use App\Models\EmployeeJobApplicationArchives;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeJobApplicationOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Count total job applications
        $totalApplications = EmployeeJobApplicationArchives::count();

        // Count approved job applications (application_status = true)
        $approvedApplications = EmployeeJobApplicationArchives::where('application_status', true)->count();

        // Count pending job applications (application_status = false)
        $pendingApplications = EmployeeJobApplicationArchives::where('application_status', false)->count();

        return [
            Stat::make('Total Job Applications', $totalApplications)
                ->color('primary')
                ->description('Total Pelamar Kerja')
                ->icon('heroicon-o-clipboard-document'),

            Stat::make('Approved Applications', $approvedApplications)
                ->color('success')
                ->description('Lamaran Diterima')
                ->icon('heroicon-o-check-circle')
                ->extraAttributes(['class' => 'text-success-500']),

            Stat::make('Pending Applications', $pendingApplications)
                ->color('danger')
                ->description('Lamaran Ditolak')
                ->icon('heroicon-o-x-circle')
                ->extraAttributes(['class' => 'text-danger-500']),
        ];
    }
}
