<?php

namespace TeamZac\POI\Drivers\Google;

class GoogleDriver 
{
    /** @var string */
    protected $apiKey;

    /**
     * Construct the driver
     * 
     * @param   array $config
     */
    public function __construct($config)
    {
        $this->apiKey = $config['key'];
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function match($term = null)
    {
        return (new MatchQuery($this->apiKey))->search($term);
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function search($term = null)
    {
        return (new SearchQuery($this->apiKey))->search($term);
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function retrieve($id)
    {
        return (new RetrieveQuery($this->apiKey))($id);
    }
}