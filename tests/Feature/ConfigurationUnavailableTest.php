<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;

class ConfigurationUnavailableTest extends TestCase
{
    /** @test */
    public function it_requires_authentication_credentials_to_send_sms()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::sms(
            'Your verification code: 34990',
            [['recipient_id' => (string) now()->timestamp, 'dest_addr' => '255753820520']]
        );
    }

    /** @test */
    public function it_requires_authentication_credentials_to_use_address_books()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::addressBooks();
    }

    /** @test */
    public function it_requires_authentication_credentials_to_check_ussd_balance()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::ussdBalance();
    }

    /** @test */
    public function it_requires_authentication_credentials_to_recharge_airtime()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::airtimeRecharge('255784123456', 2000.00, 78832);
    }

    /** @test */
    public function it_requires_authentication_credentials_to_check_airtime_transactions()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::airtimeTransaction('se56fgm');
    }

    /** @test */
    public function it_requires_authentication_credentials_to_check_airtime_balance()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::airtimeBalance();
    }

    /** @test */
    public function it_requires_authentication_credentials_to_check_payment_collection_balance()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::paymentCollectionBalance();
    }
}
