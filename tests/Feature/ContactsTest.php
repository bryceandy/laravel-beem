<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;

class ContactsTest extends TestCase
{
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
        $request = Beem::contacts('123ID');

        $this->assertTrue($request->successful());
    }
}
