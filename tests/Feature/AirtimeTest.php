<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;

class AirtimeTest extends TestCase
{
    /** @test */
    public function it_can_recharge_airtime()
    {
        $request = Beem::airtimeRecharge('255784123456', 2000.00, 78832);

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_check_airtime_transaction_status()
    {
        $request = Beem::airtimeTransaction('se56fgm');

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_check_airtime_balance()
    {
        $request = Beem::airtimeBalance();

        $this->assertTrue($request->successful());
    }
}
