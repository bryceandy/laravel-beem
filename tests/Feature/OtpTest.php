<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class OtpTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Fake all sms requests to the API
        Http::fake([
            'https://apiotp.beem.africa/v1/request' => Http::response([]),
            'https://apiotp.beem.africa/v1/verify' => Http::response([]),
        ]);
    }

    /** @test */
    public function it_can_request_otp()
    {
        $request = Beem::requestOtp();

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_verify_otp()
    {
        $request = Beem::verifyOtp();

        $this->assertTrue($request->successful());
    }
}