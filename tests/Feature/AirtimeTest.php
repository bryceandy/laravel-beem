<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class AirtimeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Fake all sms requests to the API
        Http::fake([
            'https://apiairtime.beem.africa/v1/transfer' => Http::response(json_decode(
                file_get_contents(__DIR__ . '/../stubs/airtime_recharge_response_200.json'),
                true
            )),
            'https://apiairtime.beem.africa/v1/transaction-status' => Http::response([]),
        ]);
    }

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
        Http::fake(fn () => Http::response([]));

        $request = Beem::airtimeBalance();

        $this->assertTrue($request->successful());
    }
}
