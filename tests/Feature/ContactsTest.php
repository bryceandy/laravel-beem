<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;

class ContactsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Fake all sms requests to the API
        Http::fake([
            'https://apicontacts.beem.africa/public/v1/address-books' => Http::response(json_decode(
                file_get_contents(__DIR__ . '/../stubs/address_books_response_200.json'),
                true
            )),
        ]);
    }

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

    /** @test */
    public function it_can_list_address_books()
    {
        $request = Beem::addressBooks();

        $this->assertTrue($request->successful());

        $this->assertEquals(
            'Default',
            collect($request->json()['data'])->first()['addressbook']
        );
    }
}