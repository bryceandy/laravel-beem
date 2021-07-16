<?php

namespace Bryceandy\Beem;

use Bryceandy\Beem\Facades\Beem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BeemBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/beem.php' => config_path('beem.php')
            ], 'beem-config');
        }
    }

    public function register()
    {
        $this->registerFacades();

        $this->registerRoutes();
    }

    private function registerFacades()
    {
        $this->app->singleton('Beem', fn($app) => new \Bryceandy\Beem\Beem);

        $this->app->singleton('BeemRedirect', fn($app) => new \Bryceandy\Beem\BeemRedirect);
    }

    private function registerRoutes()
    {
        $prefix = Beem::pathPrefix();

        Route::group(
            compact('prefix'),
            fn() => $this->loadRoutesFrom(__DIR__.'/../routes/web.php')
        );
    }
}