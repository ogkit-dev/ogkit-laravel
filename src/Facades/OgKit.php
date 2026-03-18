<?php

namespace OgKit\Facades;

use Illuminate\Support\Facades\Facade;

class OgKit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ogkit';
    }
}
