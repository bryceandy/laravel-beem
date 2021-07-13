<?php

namespace Bryceandy\Beem\Facades;

use Illuminate\Support\Facades\Facade;

class Beem extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Beem';
    }
}