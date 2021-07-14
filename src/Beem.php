<?php

namespace Bryceandy\Beem;

use Bryceandy\Beem\Traits\MakesHttpRequests;
use Illuminate\Http\Client\Response;

class Beem
{
    use MakesHttpRequests;

    /**
     * @param string $message
     * @param array $recipients
     * @param string $source_addr
     *
     * @return Response
     *
     * @throws Exceptions\ConfigurationUnavailableException
     */
    public function sms(string $message, array $recipients, string $source_addr = 'INFO'): Response
    {
        return $this->call(
            'https://apisms.beem.africa/v1/send',
            'POST',
            array_merge(
                ['encoding' => 0],
                compact('message', 'recipients', 'source_addr')
            )
        );
    }
}