<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;

class OtpTest extends TestCase
{
    /** @test */
    public function it_can_request_otp()
    {
        $request = Beem::requestOtp('432', '255768444000');

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_verify_otp()
    {
        $request = Beem::verifyOtp('92', '54443');

        $this->assertTrue($request->successful());
    }
}
