<?php

namespace Bryceandy\Beem\Traits\PaymentCollections;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Traits\MakesHttpRequests;
use Illuminate\Http\Client\Response;

trait HandlesPaymentCollections
{
    use MakesHttpRequests;

    /**
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function paymentCollectionBalance(): Response
    {
        return $this->callBalance('BPAY');
    }
}