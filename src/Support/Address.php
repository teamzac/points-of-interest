<?php

namespace TeamZac\POI\Support;

class Address
{
    /** @var array */
    public $attributes;

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public static function make($attributes = []) 
    {
        return new static($attributes);
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function __get($key)
    {
        return array_get($this->attributes, $key, null);
    }

    /**
     * Validate that the specified fields are present
     * 
     * @param   array $fields
     * @return  bool
     */
    public function validate($fields = [])
    {
        foreach ($fields as $key) {
            if (is_null($this->{$key})) {
                return false;
            }
        }

        return true;
    }

    /**
     * Does the address have a latitude/longitude pair
     * 
     * @return  bool
     */
    public function hasLatLng()
    {
        return $this->latLng && $this->latLng instanceof LatLng;
    }

}