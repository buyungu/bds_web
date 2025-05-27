<?php

namespace App\Providers;

use App\Models\BloodInventory;
use App\Observers\BloodInventoryObserver;
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
        BloodInventory::observe(BloodInventoryObserver::class);
    }
}
