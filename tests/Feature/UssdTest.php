<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class UssdTest extends TestCase
{
    /** @test */
    public function it_can_check_ussd_balance()
    {
        Http::fake(fn () => Http::response([]));

        $request = Beem::ussdBalance();

        $this->assertTrue($request->successful());
    }
}
