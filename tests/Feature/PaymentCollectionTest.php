<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;

class PaymentCollectionTest extends TestCase
{
    /** @test */
    public function it_can_check_payment_collection_balance()
    {
        $request = Beem::paymentCollectionBalance();

        $this->assertTrue($request->successful());
    }
}
