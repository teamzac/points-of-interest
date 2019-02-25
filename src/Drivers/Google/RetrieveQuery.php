<?php

namespace TeamZac\POI\Drivers\Google;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;

class RetrieveQuery
{
    /** @var string */
    protected $apiKey;

    public function __construct($apiKey) 
    {
        $this->apiKey = $apiKey;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function __invoke($id)
    {
        $client = new Client([
            'base_uri' => 'https://maps.googleapis.com/maps/api/place/details/json',
            'timeout'  => 10.0,
            'stream' => false,
        ]);

        $response = $client->get('', [
            'headers' => [
                'Accept'     => 'application/json',
            ],
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

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function mapResultToPlace($result)
    {
        return (new Place)->setRaw($result)->map([
            'provider' => 'google',
            'id' => Arr::get($result, 'place_id'),
            'name' => Arr::get($result, 'name'),
            'address' => new Address([
                'formatted' => Arr::get($result, 'formatted_address'),
                'latLng' => new LatLng(
                    Arr::get($result, 'geometry.location.lat'), Arr::get($result, 'geometry.location.lng')
                ),
            ]),
            'categories' => Arr::get($result, 'types', [])
        ]);
    }
}