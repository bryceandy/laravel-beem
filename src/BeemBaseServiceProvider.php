<?php

namespace Bryceandy\Beem;

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
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}