<?php

namespace Bryceandy\Beem\Tests;

use Bryceandy\Beem\BeemBaseServiceProvider;
use Illuminate\Foundation\Application;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Register service providers
     *
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders(Application $app): array
    {
        return [
            BeemBaseServiceProvider::class,
        ];
    }
}