<?php

namespace Bryceandy\Beem\Events;

use Illuminate\Http\Request;

class UssdCallbackReceived
{
    public Request $request;

    /**
     * UssdCallbackReceived constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}