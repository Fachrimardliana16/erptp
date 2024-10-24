<?php

namespace App\Providers;

use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Facades\Schema;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Table::configureUsing(function (Table $table): void {
            $table
                ->emptyStateHeading('No data yet')
                ->striped()
                ->defaultPaginationPageOption(10)
                ->paginated([10, 25, 50, 100])
                ->extremePaginationLinks()
                ->defaultSort('created_at', 'desc');
        });

        $migrationPaths = [
            database_path('migrations/assets/master_assets'),
            database_path('migrations/assets/triggers'),
            database_path('migrations/assets/assets'),
            database_path('migrations/employees/master_employee'),
            database_path('migrations/employees/triggers'),
            database_path('migrations/employees/employees'),
            database_path('migrations/inventory/master_inventory'),
            database_path('migrations/inventory/triggers'),
            database_path('migrations/inventory/inventory'),
            database_path('migrations/logger/master_logger'),
            database_path('migrations/logger/triggers'),
            database_path('migrations/logger/logger'),
            database_path('migrations/billing/master_billing'),
            database_path('migrations/billing/triggers'),
            database_path('migrations/billing/billing'),
            database_path('migrations/master'),

        ];

        foreach ($migrationPaths as $path) {
            $this->loadMigrationsFrom($path);
        }
    }
}
