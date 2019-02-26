<?php

namespace TeamZac\POI\Drivers\Yelp;

use TeamZac\POI\Support\HttpClient;

class Client extends HttpClient
{
    public $baseUri = 'https://api.yelp.com/v3/businesses/';

    protected $apiKey;

    public function __construct($apiKey) 
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @inheritdoc
     */
    public function defaultHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'accept' => 'application/json',
        ];
    }
    
}