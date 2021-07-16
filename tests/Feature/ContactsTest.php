<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;
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

    /** @test */
    public function it_can_list_contacts_of_an_address_book()
    {
        Http::fake(fn () => Http::response([]));

        $request = Beem::contacts('123ID');

        $this->assertTrue($request->successful());
    }
}
