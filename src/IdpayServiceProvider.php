<?php

namespace Mdafzaran\Idpay;

use Illuminate\Support\ServiceProvider;

class IdpayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton(MainPay::class, function () {
        //     return new MainPay();
        // });

        // $this->app->alias(MainPay::class, 'MainPay');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        
    }
}
