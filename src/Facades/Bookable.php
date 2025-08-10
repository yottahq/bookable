<?php

namespace YottaHQ\Bookable\Facades;

use Illuminate\Support\Facades\Facade;

class Bookable extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bookable';
    }
}
