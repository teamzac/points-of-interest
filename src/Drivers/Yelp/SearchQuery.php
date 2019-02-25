<?php

namespace TeamZac\POI\Drivers\Yelp;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Support\PlaceCollection;

class SearchQuery
{
    /** @var string */
    protected $endpointUrl = 'https://api.yelp.com/v3/businesses/search';

    /** @var string */
    protected $apiKey;

    /** @var array */
    protected $query = [
        'limit' => 10,
        'offset' => 0,
        'sort_by' => 'distance',
    ];

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
        $this->query['term'] = $searchTerm;

        return $this;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function categories(array $categories)
    {
        $this->query['categories'] = implode(',', $categories);

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
            'latitude' => $address->latLng->getLat(),
            'longitude' => $address->latLng->getLng(),
        ], $this->query);

        return $this;
    }

    /**
     * Use the provider's distance sorting algorithm
     * 
     * @return  $this
     */
    public function sortByDistance()
    {
        $this->query['sort_by'] = 'distance';
        return $this;
    }

    /**
     * Use the provider's default sorting algorithm
     * 
     * @return  $this
     */
    public function sortByDefault()
    {
        $this->query['sort_by'] = 'best_match';
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
            'base_uri' => $this->endpointUrl,
            'timeout'  => 10.0,
            'stream' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]
        ]);

        $response = $client->get('', [
            'headers' => [
                'Accept'     => 'application/json',
            ],
            'query' => $this->buildQueryString(),
        ]);

        if ( $response->getStatusCode() >= 400 )
        {
            throw new \Exception('Unable to process Geocoding');
        }

        $json = json_decode($response->getBody()->getContents(), true);

        return PlaceCollection::make($this->mapResults(Arr::get($json, 'businesses', [])))
            ->setProvider('yelp')
            ->setCursor($this->getCursor());
    }

    /**
     * Build the query string, overriding any query params already set as needed
     * 
     * @param   array $attributes
     * @return  string
     */
    public function buildQueryString($attributes = [])
    {
        return http_build_query(array_merge($attributes, $this->query));
    }

    /**
     * Create a cursor URL, since Yelp does not provide one
     * 
     * @return  string
     */
    public function getCursor()
    {
        return sprintf(
            '%s?%s',
            $this->endpointUrl,
            $this->buildQueryString([
                'offset' => $this->query['offset'] += $this->query['limit'],
            ])
        );
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
            'phone' => Arr::get($result, 'display_phone'),
            'categories' => collect(Arr::get($result, 'categories', []))->map(function($category) {
                return $category['alias'];
            })->toArray(),
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
        $queryString = parse_url($cursor, PHP_URL_QUERY);
        parse_str($queryString, $this->query);
        return $this->get();
    }
}
