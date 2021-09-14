<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Facades\BeemRedirect;
use Bryceandy\Beem\Tests\TestCase;
use Illuminate\Foundation\Application;

class ConfigurationUnavailableTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set([
            'beem.api_key' => null,
            'beem.secret_key' => null,
        ]);
    }

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

    /** @test */
    public function it_requires_authentication_credentials_to_redirect_to_payment_checkout()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        BeemRedirect::checkout(
            '1200',
            '96f9cc09-afa0-40cf-928a-d7e2b27b2408',
            'SAMPLE-12345'
        );
    }

    /** @test */
    public function it_requires_authentication_credentials_to_disburse_payments()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::disburse(
            '20000',
            '11212919190101',
            'f09dc0d3',
            '255701000000',
            'ABC12345'
        );
    }

    /** @test */
    public function it_requires_authentication_credentials_to_request_otp()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::requestOtp('432', '255768444000');
    }

    /** @test */
    public function it_requires_authentication_credentials_to_verify_otp()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::verifyOtp('92', '54443');
    }
}
