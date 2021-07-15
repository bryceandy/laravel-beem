<?php

namespace Bryceandy\Beem\Http\Controllers;

use Bryceandy\Beem\Events\AirtimeCallbackReceived;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AirtimeCallbackController extends Controller
{
    /**
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        AirtimeCallbackReceived::dispatch($request);
    }
}