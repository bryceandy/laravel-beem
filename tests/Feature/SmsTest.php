<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;

class SmsTest extends TestCase
{
    /** @test */
    public function it_can_send_an_sms()
    {
        $request = Beem::sms(
            'Your verification code: 34990',
            [['recipient_id' => (string) now()->timestamp, 'dest_addr' => '255753820520']]
        );

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_send_a_scheduled_sms()
    {
        $request = Beem::smsWithSchedule(
            'Your new message',
            [['recipient_id' => (string) now()->timestamp, 'dest_addr' => '255753820520']],
            now()
        );

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_check_sms_balance()
    {
        $request = Beem::smsBalance();

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_fetch_sender_names()
    {
        $request = Beem::smsSenderNames();

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_request_sender_names()
    {
        $request = Beem::requestSmsSenderName('BRYCEANDY', 'A sample message');

        $this->assertTrue($request->successful());
    }
}
