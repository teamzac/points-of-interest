<?php

namespace TeamZac\POI\Drivers\Google;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Contracts\RetrieveQueryInterface;

class RetrieveQuery implements RetrieveQueryInterface
{
    use MapsGoogleResults;
    
    /** @var GuzzleHttp\Client */
    protected $client;

    /** @var string */
    protected $apiKey;

    public function __construct($client, $apiKey) 
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $response = $this->client->get('details/json', [
            'query' => [
                'placeid' => $id,
                'key' => $this->apiKey,
                'fields' => 'id,name,place_id,geometry/location,formatted_address,permanently_closed,types',
            ]
        ]);

        if ( $response->getStatusCode() >= 400 )
        {
            throw new \Exception('Unable to process Geocoding');
        }

        $json = json_decode($response->getBody()->getContents(), true);

        return $this->mapResultToPlace(Arr::get($json, 'result', []));
    }
}