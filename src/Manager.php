<?php

namespace TeamZac\POI;

use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * Create an instance of the specified driver
     * 
     * @return  GoogleDriver
     */
    protected function createGoogleDriver()
    {
        return new Drivers\Google\GoogleDriver($this->app['config']['points-of-interest.connections.google']);
    }

    /**
     * Get the default driver name.
     * 
     * @return  string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['points-of-interest.default'];
    }
}
