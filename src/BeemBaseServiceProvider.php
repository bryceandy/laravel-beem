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
    }

    private function registerRoutes()
    {
        Route::group([
            'prefix' => Beem::pathPrefix(),
            'namespace' => 'Bryceandy\Beem\Http\Controllers',
        ],
        fn() => $this->loadRoutesFrom(__DIR__.'/../routes/web.php'));
    }
}