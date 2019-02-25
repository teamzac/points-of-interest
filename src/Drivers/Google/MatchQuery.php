<?php

namespace TeamZac\POI\Drivers\Google;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;

class MatchQuery
{
    /** @var array */
    protected $query = [
        'inputtype' => 'textquery',
        'fields' => 'id,name,place_id,geometry/location,formatted_address,permanently_closed,photos,types',
    ];

    /**
     * Construct the query
     * 
     * @param   array $key
     */
    public function __construct($key)
    {
        $this->query['key'] = $key;
    }
    
    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function search($searchTerm)
    {
        if (is_numeric($searchTerm)) {
            return $this->phone($searchTerm);
        }

        $this->query['input'] = $searchTerm;
        $this->query['inputtype'] = 'textquery';

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
        $this->query['locationbias'] = sprintf('point:%s,%s', $address->latLng->getLat(), $address->latLng->getLng());

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
            'base_uri' => 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json',
            'timeout'  => 10.0,
            'stream' => false,
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

        return $this->mapResultToPlace(Arr::get($json, 'candidates', [])[0]);
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
