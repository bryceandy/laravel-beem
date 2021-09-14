<?php

namespace Bryceandy\Beem\Tests;

use Bryceandy\Beem\BeemBaseServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Mockery;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'beem.africa/public/v1/address-books' => Http::response(json_decode(
                file_get_contents(__DIR__ . '/stubs/address_books_response_200.json'),
                true
            )),
        ]);

        Http::fake();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set([
            'beem.api_key' => '12345',
            'beem.secret_key' => 'abc',
        ]);
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