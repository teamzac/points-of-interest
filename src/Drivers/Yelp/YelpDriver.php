<?php

namespace TeamZac\POI\Drivers\Yelp;

use TeamZac\POI\Contracts\ProviderInterface;

class YelpDriver implements ProviderInterface
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
     * @inheritdoc
     */
    public function match($term = null)
    {
        return (new MatchQuery($this->yelpClient()))->search($term);
    }

    /**
     * @inheritdoc
     */
    public function search($term = null)
    {
        return (new SearchQuery($this->yelpClient()))->search($term);
    }

    /**
     * @inheritdoc
     */
    public function retrieve($id) 
    {
        return (new RetrieveQuery($this->yelpClient()))->get($id);
    }

    protected function yelpClient() 
    {
        return new Client($this->apiKey);
    }
}