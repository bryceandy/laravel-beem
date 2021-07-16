<?php

namespace Bryceandy\Beem;

use Bryceandy\Beem\Traits\Airtime\HandlesAirtime;
use Bryceandy\Beem\Traits\Contacts\HandlesContacts;
use Bryceandy\Beem\Traits\Disbursements\HandlesDisbursements;
use Bryceandy\Beem\Traits\Otp\HandlesOtp;
use Bryceandy\Beem\Traits\PaymentCollections\HandlesPaymentCollections;
use Bryceandy\Beem\Traits\Sms\HandlesSms;
use Bryceandy\Beem\Traits\Ussd\HandlesUssd;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;

class Beem
{
    use HandlesAirtime;
    use HandlesContacts;
    use HandlesDisbursements;
    use HandlesOtp;
    use HandlesPaymentCollections;
    use HandlesSms;
    use HandlesUssd;

    /**
     * Fetch the prefix name for all routes
     *
     * @return Repository|Application|mixed
     */
    public function pathPrefix()
    {
        return config('beem.path');
    }
}