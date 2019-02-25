<?php

namespace TeamZac\POI\Drivers\Google;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Support\PlaceCollection;

class SearchQuery
{
    /** @var array */
    protected $query = [
        'fields' => 'id,name,place_id,formatted_address,permanently_closed,photos,types',
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
    public function search($searchTerm = null)
    {
        $this->query['keyword'] = $searchTerm;

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
        $this->query['location'] = sprintf('%s,%s', $address->latLng->getLat(), $address->latLng->getLng());
        $this->query['rankby'] = 'distance';

        return $this;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function within($meters)
    {
        $this->query['radius'] = $meters;
        $this->query['rankby'] = 'prominence';

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
            'base_uri' => 'https://maps.googleapis.com/maps/api/place/nearbysearch/json',
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

        return PlaceCollection::make($this->mapResults(Arr::get($json, 'results', [])))
            ->setProvider('google')
            ->setCursor(Arr::get($json, 'next_page_token'));
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function mapResults($results)
    {
        return collect($results)->map(function($result) {
            return $this->mapResultToPlace($result);
        });
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

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function fromCursor($cursor)
    {
        $this->query = [
            'pagetoken' => $cursor,
        ];

        return $this->get();
    }
}
