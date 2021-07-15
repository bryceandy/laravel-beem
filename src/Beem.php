<?php

namespace Bryceandy\Beem;

use Bryceandy\Beem\Traits\Contacts\HandlesContacts;
use Bryceandy\Beem\Traits\Sms\HandlesSms;
use Bryceandy\Beem\Traits\Ussd\HandlesUssd;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;

class Beem
{
    use HandlesSms;
    use HandlesContacts;
    use HandlesUssd;

    /**
     * Fetch the prefix name for all routes
     *
     * @return Repository|Application|mixed
     */
    public function pathPrefix()
    {
        return config('beem.path', 'beem');
    }
}