<?php

namespace Bryceandy\Beem;

use Illuminate\Support\ServiceProvider;

class BeemBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/beem.php' => config_path('beem.php')
        ], 'beem-config');
    }
}