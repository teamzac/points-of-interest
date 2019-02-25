<?php

namespace TeamZac\POI\Drivers\Here;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Support\PlaceCollection;
use TeamZac\POI\Drivers\Here\MapsHereResults;

class SearchQuery 
{
    use MapsHereResults;

    /** @var GuzzleHttp\Client */
    protected $client;

    /** @var array */
    protected $credentials;

    /** @var array */
    protected $query = [
        'show_refs' => 'facebook,yelp,opentable',
    ];

    public function __construct($client, $credentials)
    {
        $this->client = $client;
        $this->credentials = $credentials;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function search($term = null)
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function near(Address $address)
    {
        $this->query['at'] = $address->latLng->getDescription();
        return $this;
    }

    /**
     * @inherit
     */
    public function get()
    {
        $response = $this->client->get('discover/explore', [
            'query' => array_merge($this->query, $this->credentials)
        ]);

        if ( $response->getStatusCode() >= 400 )
        {
            throw new \Exception('Unable to process Geocoding');
        }

        $json = json_decode($response->getBody()->getContents(), true);

        return PlaceCollection::make($this->mapResults(Arr::get($json, 'results.items', [])))
            ->setProvider('here')
            ->setCursor(Arr::get($json, 'results.next'));
    }

    /**
     * Map all of the results to Place objects
     * 
     * @param   array $results
     * @return  Collection
     */
    public function mapResults($results)
    {
        return collect($results)->map(function($result) {
            return $this->mapResultToPlace($result);
        });
    }
    
}