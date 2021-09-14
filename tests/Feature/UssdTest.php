<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;

class UssdTest extends TestCase
{
    /** @test */
    public function it_can_check_ussd_balance()
    {
        $request = Beem::ussdBalance();

        $this->assertTrue($request->successful());
    }
}
