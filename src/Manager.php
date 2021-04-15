<?php

namespace TeamZac\POI;

use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * Create an instance of the specified driver.
     *
     * @return  GoogleDriver
     */
    protected function createGoogleDriver()
    {
        return new Drivers\Google\GoogleDriver($this->container['config']['points-of-interest.connections.google']);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return  YelpDriver
     */
    protected function createYelpDriver()
    {
        return new Drivers\Yelp\YelpDriver($this->container['config']['points-of-interest.connections.yelp']);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return  HereDriver
     */
    protected function createHereDriver()
    {
        return new Drivers\Here\HereDriver($this->container['config']['points-of-interest.connections.here']);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return  FourSquareDriver
     */
    protected function createFourSquareDriver()
    {
        return new Drivers\FourSquare\FourSquareDriver($this->container['config']['points-of-interest.connections.foursquare']);
    }

    /**
     * Get the default driver name.
     *
     * @return  string
     */
    public function getDefaultDriver()
    {
        return $this->container['config']['points-of-interest.default'];
    }
}
