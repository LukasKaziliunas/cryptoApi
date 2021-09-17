<?php

namespace App\Providers;

use App\Interfaces\CryptoApi;
use App\Services\CoinlayerApi;
use App\Services\CoinMarketCapApi;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $default = config('crypto.default');
        $cryptoApi = config("crypto.{$default}.class");

        $this->app->bind(CryptoApi::class, $cryptoApi);
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
