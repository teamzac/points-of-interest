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

}