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
        
        // Configure SSL certificate for cURL
        if (app()->environment('local')) {
            $this->configureCurlCertificate();
        }
    }
    
    private function configureCurlCertificate()
    {
        $cacertPath = env('CURL_CA_BUNDLE');
        
        if ($cacertPath && file_exists($cacertPath)) {
            // Set CA bundle path for cURL
            ini_set('curl.cainfo', $cacertPath);
            
            // Configure Guzzle HTTP client with CA bundle
            $this->app->bind('guzzle.config', function () use ($cacertPath) {
                return [
                    'verify' => $cacertPath,
                    'timeout' => 30,
                ];
            });
        } else {
            // Fallback: disable SSL verification
            $this->app->bind('guzzle.config', function () {
                return [
                    'verify' => false,
                    'timeout' => 30,
                ];
            });
        }
    }
}
