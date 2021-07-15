<?php

namespace Bryceandy\Beem\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class CallbackReceived
{
    use Dispatchable, InteractsWithSockets;

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