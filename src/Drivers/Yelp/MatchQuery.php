<?php

namespace TeamZac\POI\Drivers\Yelp;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;

class MatchQuery
{
    /** @var string */
    protected $apiKey;

    /** @var array */
    protected $query = [];

    /**
     * Construct the query
     * 
     * @param   array $key
     */
    public function __construct($key)
    {
        $this->apiKey = $key;
    }
    
    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function search($searchTerm)
    {
        $this->query['name'] = $searchTerm;

        return $this;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function phone($phone)
    {
        $this->query['input'] = $phone;
        $this->query['inputtype'] = 'phonenumber';

        return $this;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function near($address)
    {
        $this->query = array_merge([
            'address1' => $address->street,
            'city' => $address->city,
            'state' => $address->state,
            'country' => $address->country,
            'zip_code' => $address->postalCode,
        ], $this->query);

        return $this;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function get()
    {
        $client = new Client([
            'base_uri' => 'https://api.yelp.com/v3/businesses/matches',
            'timeout'  => 10.0,
            'stream' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]
        ]);

        $queryString =  http_build_query($this->query);

        $response = $client->get('', [
            'headers' => [
                'Accept'     => 'application/json',
            ],
            'query' => $this->query,
        ]);

        if ( $response->getStatusCode() >= 400 )
        {
            throw new \Exception('Unable to process Geocoding');
        }

        $json = json_decode($response->getBody()->getContents(), true);

        return $this->mapResultToPlace($json['businesses'][0]);
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
            'provider' => 'yelp',
            'id' => Arr::get($result, 'id'),
            'name' => Arr::get($result, 'name'),
            'address' => new Address([
                'street' => Arr::get($result, 'location.address1'),
                'city' => Arr::get($result, 'location.city'),
                'state' => Arr::get($result, 'location.state'),
                'postalCode' => Arr::get($result, 'location.zip_code'),
                'country' => Arr::get($result, 'location.country'),
                'formatted' => implode(', ', Arr::get($result, 'location.display_address')),
                'latLng' => new LatLng(
                    Arr::get($result, 'coordinates.latitude'), Arr::get($result, 'coordinates.longitude')
                ),
            ]),
            'phone' => Arr::get($result, 'display_phone')
        ]);
    }
}
