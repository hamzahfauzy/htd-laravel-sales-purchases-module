<?php

namespace App\Modules\SalesPurchases\Providers;

use App\Libraries\Dashboard;
use App\Libraries\NavPanel;
use Illuminate\Support\ServiceProvider;

class SalesPurchasesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Databases/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'sales-purchases');

        Dashboard::add(\App\Modules\SalesPurchases\Services\DashboardService::revenue());
        Dashboard::add(\App\Modules\SalesPurchases\Services\DashboardService::topStatistic());

        NavPanel::add([
            'url' => url('/pos'),
            'label' => 'Pos Panel',
            'icon' => 'bx bxs-registered',
        ]);
    }
}
