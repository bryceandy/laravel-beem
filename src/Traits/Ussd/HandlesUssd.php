<?php

namespace Bryceandy\Beem\Traits\Ussd;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Traits\MakesHttpRequests;
use Illuminate\Http\Client\Response;

trait HandlesUssd
{
    use MakesHttpRequests;

    /**
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function ussdBalance(): Response
    {
        return $this->callBalance('USSD');
    }
}