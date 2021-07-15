<?php

namespace Bryceandy\Beem;

use Bryceandy\Beem\Traits\Contacts\HandlesContacts;
use Bryceandy\Beem\Traits\Sms\HandlesSms;
use Bryceandy\Beem\Traits\Ussd\HandlesUssd;

class Beem
{
    use HandlesSms;
    use HandlesContacts;
    use HandlesUssd;
}