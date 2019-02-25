<?php

namespace TeamZac\POI\Support;

class Place
{
    /** @var string */
    protected $provider;

    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var TeamZac\POI\Support\Address */
    protected $address;

    /** @var string */
    protected $phone;

    /** @var array */
    protected $categories = [];

    /** @var array */
    protected $raw;

    /**
     * Get the provider
     * 
     * @return  name
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Get the remote ID associated with this place
     * 
     * @return  string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the name associated with this place
     * 
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the address record associated with this place
     * 
     * @return  TeamZac\POI\Support\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get a phone number associated with this place
     * 
     * @return  string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get the categories for this place
     * 
     * @return  array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Get the raw response from the provider
     * 
     * @return  array
     */
    public function getRaw()
    {
        return $this->raw;
    }


    /**
     * Set the raw response from the provider
     * 
     * @param   array
     * @return  $this
     */
    public function setRaw($raw) 
    {
        $this->raw = $raw;
        return $this;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function map($attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }
}   