<?php

namespace TeamZac\POI\Drivers\FourSquare;

use GuzzleHttp\Client as Guzzle;

class Client
{
    /** @var GuzzleHttp\Client */
    protected $guzzleClient;
    
    /** @var array */
    protected $credentials;

    /** @var string */
    protected $apiVersion = '20190225';

    public function __construct($credentials) 
    {
        $this->credentials = $credentials;

        $this->guzzleClient = new Guzzle([
            'base_uri' => 'https://api.foursquare.com/v2/',
            'timeout' => 10.0,
            'debug' => false,
        ]);
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function get($endpoint, $queryParams = [])
    {
        $response = $this->guzzleClient->get($endpoint, [
            'query' => array_merge(
                $queryParams, 
                $this->credentials, 
                [
                    'v' => $this->apiVersion
                ]
            )
        ]);

        if ( $response->getStatusCode() >= 400 )
        {
            throw new \Exception('Unable to process Geocoding');
        }
        
        return json_decode($response->getBody()->getContents(), true);
    }
}