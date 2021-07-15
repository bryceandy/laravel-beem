<?php

namespace Bryceandy\Beem\Http\Controllers;

use Bryceandy\Beem\Events\TwoWaySmsCallbackReceived;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TwoWaySmsCallbackController extends Controller
{
    /**
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        TwoWaySmsCallbackReceived::dispatch($request);
    }
}