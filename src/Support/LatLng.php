<?php

namespace TeamZac\POI\Support;

class LatLng
{
    /** @var float */
    protected $lat;

    /** @var float */
    protected $lng;

    public function __construct($lat, $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public static function make($lat, $lng)
    {
        return new static($lat, $lng);
    }

    /**
     * @param
     * @return
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param
     * @return
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param
     * @return
     */
    public function getDescription()
    {
        return sprintf('%s,%s', $this->lat, $this->lng);
    }
}
