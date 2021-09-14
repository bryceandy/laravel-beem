<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;

class DisbursementTest extends TestCase
{
    /** @test */
    public function it_can_disburse_payments()
    {
        $request = Beem::disburse(
            '20000',
            '11212919190101',
            'f09dc0d3',
            '255701000000',
            'ABC12345'
        );

        $this->assertTrue($request->successful());
    }
}
