<?php

namespace TeamZac\POI\Facades;

use Illuminate\Support\Facades\Facade;

class POI extends Facade
{
    /**
     * @param
     * @return
     */
    protected static function getFacadeAccessor()
    {
        return 'poi';
    }
}
