<?php

namespace TeamZac\POI\Drivers\Yelp;

use GuzzleHttp\Client;
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
        return new Client([
            'base_uri' => 'https://api.yelp.com/v3/businesses/',
            'timeout'  => 10.0,
            'stream' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ]
        ]);
    }
}