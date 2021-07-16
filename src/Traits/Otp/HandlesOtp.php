<?php

namespace Bryceandy\Beem\Traits\Otp;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Traits\MakesHttpRequests;
use Illuminate\Http\Client\Response;

trait HandlesOtp
{
    use MakesHttpRequests;

    /**
     * @param string $appId
     * @param string $msisdn
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function requestOtp(string $appId, string $msisdn): Response
    {
        return $this->call(
            'https://apiotp.beem.africa/v1/request',
            'POST',
            compact('appId', 'msisdn')
        );
    }
}