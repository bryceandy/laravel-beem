<?php

namespace Bryceandy\Beem\Http\Controllers;

use Bryceandy\Beem\Events\PaymentCheckoutCallbackReceived;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaymentCheckoutCallbackController extends Controller
{
    /**
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        PaymentCheckoutCallbackReceived::dispatch($request);
    }
}