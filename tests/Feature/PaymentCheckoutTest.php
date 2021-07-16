<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;

class PaymentCheckoutTest extends TestCase
{
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
    public function it_can_redirect_to_payment_checkout()
    {
        Http::fake([
            'https://checkout.beem.africa/v1/checkout' . '*' => Http::response(json_decode(
                file_get_contents(__DIR__ . '/../stubs/payment_checkout_response_200.json'),
                true
            ))
        ]);

        $request = Beem::redirectPaymentCheckout(
            '1200',
            '96f9cc09-afa0-40cf-928a-d7e2b27b2408',
            'SAMPLE-12345'
        );

        $this->assertTrue($request->successful());

        $this->assertStringContainsString('https://', $request->json()['src']);
    }
}
