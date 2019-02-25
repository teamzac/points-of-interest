<?php

namespace TeamZac\POI\Drivers\Yelp;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Contracts\RetrieveQueryInterface;

class RetrieveQuery implements RetrieveQueryInterface
{
    use MapsYelpResults;
    
    /** @var GuzzleHttp\Client */
    protected $client;

    public function __construct($client) 
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $response = $this->client->get($id);

        if ( $response->getStatusCode() >= 400 )
        {
            throw new \Exception('Unable to process Geocoding');
        }

        $json = json_decode($response->getBody()->getContents(), true);

        return $this->mapResultToPlace($json);
    }

}