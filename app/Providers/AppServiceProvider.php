<?php

namespace App\Providers;

use Google_Client;
use Google_Service_Sheets;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @retuarn void
     */
    public function register(): void
    {
        $this->app->singleton(Google_Client::class, function () {
            $client = new Google_Client();
            $client->setApplicationName(config('app.name'));
            $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
            $client->setAuthConfig(config('google.credentials'));
            return $client;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
