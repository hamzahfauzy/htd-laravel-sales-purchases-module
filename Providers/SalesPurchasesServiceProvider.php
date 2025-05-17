<?php

namespace App\Modules\SalesPurchases\Providers;

use Illuminate\Support\ServiceProvider;

class SalesPurchasesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Databases/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'sales-purchases');
    }
}
