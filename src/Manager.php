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
     * Create an instance of the specified driver
     * 
     * @return  YelpDriver
     */
    protected function createYelpDriver()
    {
        return new Drivers\Yelp\YelpDriver($this->app['config']['points-of-interest.connections.yelp']);
    }

    /**
     * Create an instance of the specified driver
     * 
     * @return  HereDriver
     */
    protected function createHereDriver()
    {
        return new Drivers\Here\HereDriver($this->app['config']['points-of-interest.connections.here']);
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
