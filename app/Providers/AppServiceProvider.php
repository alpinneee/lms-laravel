<?php

namespace App\Providers;

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
        // Fix MySQL strict mode issues with GROUP BY
        if (config('database.default') === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("SET SQL_MODE=''");
        }
    }
}
