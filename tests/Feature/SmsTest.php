<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;

class SmsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Fake all sms requests to the API
        Http::fake([
           'https://apisms.beem.africa/v1/send' => Http::response([
               'successful' => true,
               'request_id' => 67,
               'code' => 100,
               'message' => 'Message Submitted Successfully',
               'valid' => 1,
               'invalid' => 0,
               'duplicates' => 0,
           ]),
        ]);
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('beem.api_key', '12345');
        $app['config']->set('beem.secret_key', 'abc');
    }

    /** @test */
    public function it_can_send_an_sms()
    {
        $request = Beem::sms(
            'Your verification code: 34990',
            [['recipient_id' => (string) now()->timestamp, 'dest_addr' => '255753820520']]
        );

        $this->assertTrue($request->successful());
    }
}
