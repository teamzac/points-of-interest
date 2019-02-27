<?php

namespace TeamZac\POI\Support;

use TeamZac\POI\Support\LatLng;

class BoundingBox
{
    /** @var LatLng */
    protected $nw;

    /** @var LatLng */
    protected $ne;

    /** @var LatLng */
    protected $se;

    /** @var LatLng */
    protected $sw;

    public static function fromNorthWest($nw, $se) 
    {
        return (new static)->setNorthWest($nw)->setSouthEast($se);
    }

    public static function fromNorthEast($ne, $sw) 
    {
        return (new static)->setNorthEast($ne)->setSouthWest($sw);
    }

    public static function fromSouthWest($sw, $ne) 
    {
        return (new static)->setSouthWest($sw)->setNorthEast($ne);
    }

    /**
     * Set the northwest corner.
     *
     * @param   LatLng $nw
     * @return  $this
     */
    public function setNorthWest($nw) 
    {
        $this->nw = $nw;
        return $this;
    }

    /**
     * Set the northeast corner.
     *
     * @param   LatLng $ne
     * @return  $this
     */
    public function setNorthEast($ne) 
    {
        $this->ne = $ne;
        return $this;
    }

    /**
     * Set the southeast corner.
     *
     * @param   LatLng $se
     * @return  $this
     */
    public function setSouthEast($se) 
    {
        $this->se = $se;
        return $this;
    }

    /**
     * Set the southwest corner.
     *
     * @param   LatLng $sw
     * @return  $this
     */
    public function setSouthWest($sw) 
    {
        $this->sw = $sw;
        return $this;
    }

    /**
     * Get the northwest corner.
     * 
     * @return  LatLng
     */
    public function nw()
    {
        if (is_null($this->nw)) {
            $this->validate($this->sw, $this->ne);
            $this->nw = LatLng::make($this->ne->getLat(), $this->sw->getLng());
        }

        return $this->nw;
    }

    /**
     * Get the southeast corner.
     * 
     * @return  LatLng
     */
    public function se()
    {
        if (is_null($this->se)) {
            $this->validate($this->sw, $this->ne);
            $this->se = LatLng::make($this->sw->getLat(), $this->ne->getLng());
        }

        return $this->se;
    }

    /**
     * Get the northeast corner.
     * 
     * @return  LatLng
     */
    public function ne()
    {
        if (is_null($this->ne)) {
            $this->validate($this->nw, $this->se);
            $this->ne = LatLng::make($this->nw->getLat(), $this->se->getLng());
        }

        return $this->ne;
    }

    /**
     * Get the southwest corner.
     * 
     * @return  LatLng
     */
    public function sw()
    {
        if (is_null($this->sw)) {
            $this->validate($this->nw, $this->se);
            $this->sw = LatLng::make($this->se->getLat(), $this->nw->getLng());
        }

        return $this->sw;
    }

    /**
     * Validate that the given corners are not null.
     * 
     * @param   LatLng
     * @throws  Exception
     */
    public function validate(...$latLngs)
    {
        foreach ($latLngs as $latLng) {
            if (is_null($latLng)) {
                throw new \Exception('Insufficient coordinates to generate this corner');
            }
        }
    }
}