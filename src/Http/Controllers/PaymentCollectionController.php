<?php

namespace Bryceandy\Beem\Http\Controllers;

use Bryceandy\Beem\Events\PaymentCollectionReceived;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaymentCollectionController extends Controller
{
    /**
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        PaymentCollectionReceived::dispatch($request);
    }
}