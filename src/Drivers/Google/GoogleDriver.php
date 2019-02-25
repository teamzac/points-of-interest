<?php

namespace TeamZac\POI\Drivers\Google;

use GuzzleHttp\Client;
use TeamZac\POI\Contracts\ProviderInterface;

class GoogleDriver implements ProviderInterface
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
        return (new MatchQuery($this->googleClient(), $this->apiKey))->search($term);
    }

    /**
     * @inheritdoc
     */
    public function search($term = null)
    {
        return (new SearchQuery($this->googleClient(), $this->apiKey))->search($term);
    }

    /**
     * @inheritdoc
     */
    public function retrieve($id)
    {
        return (new RetrieveQuery($this->googleClient(), $this->apiKey))->get($id);
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    protected function googleClient()
    {
        return new Client([
            'base_uri' => 'https://maps.googleapis.com/maps/api/place/',
            'timeout'  => 10.0,
            'stream' => false,
            'headers' => [
                'Accept'     => 'application/json',
            ],
        ]);
    }
}