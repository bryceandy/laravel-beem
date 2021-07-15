<?php

namespace Bryceandy\Beem\Http\Controllers;

use Bryceandy\Beem\Events\UssdCallbackReceived;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UssdCallbackController extends Controller
{
    /**
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        UssdCallbackReceived::dispatch($request);
    }
}