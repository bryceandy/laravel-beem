<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class PaymentCollectionTest extends TestCase
{
    /** @test */
    public function it_can_check_payment_collection_balance()
    {
        Http::fake(fn () => Http::response([]));

        $request = Beem::paymentCollectionBalance();

        $this->assertTrue($request->successful());
    }
}
