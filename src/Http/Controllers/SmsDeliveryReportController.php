<?php

namespace Bryceandy\Beem\Http\Controllers;

use Bryceandy\Beem\Events\SmsDeliveryReportReceived;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SmsDeliveryReportController extends Controller
{
    /**
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        SmsDeliveryReportReceived::dispatch($request);
    }
}