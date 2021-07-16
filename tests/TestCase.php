<?php

namespace Bryceandy\Beem\Tests;

use Bryceandy\Beem\BeemBaseServiceProvider;
use Illuminate\Foundation\Application;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Define environment setup.
     *
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('beem.api_key', '12345');
        $app['config']->set('beem.secret_key', 'abc');
    }

    /**
     * Register service providers
     *
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            BeemBaseServiceProvider::class,
        ];
    }
}