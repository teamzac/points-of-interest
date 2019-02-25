<?php

namespace TeamZac\POI\Drivers\Yelp;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Support\PlaceCollection;
use TeamZac\POI\Contracts\SearchQueryInterface;

class SearchQuery implements SearchQueryInterface
{
    use MapsYelpResults;

    /** @var GuzzleHttp\Client */
    protected $client;

    /** @var string */
    protected $endpointUrl = 'https://api.yelp.com/v3/businesses/search';

    /** @var array */
    protected $query = [
        'limit' => 10,
        'offset' => 0,
        'sort_by' => 'distance',
    ];

    /**
     * Construct the query
     * 
     * @param   GuzzleHttp\Client $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }
    
    /**
     * @inheritdoc
     */
    public function search($term = null)
    {
        $this->query['term'] = $term;

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
     * @inheritdoc
     */
    public function near(Address $address)
    {
        $this->query = array_merge([
            'latitude' => $address->latLng->getLat(),
            'longitude' => $address->latLng->getLng(),
        ], $this->query);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function within($geometry)
    {
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
     * @inheritdoc
     */
    public function get()
    {
        $response = $this->client->get($this->endpointUrl, [
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
     * @inheritdoc
     */
    public function fromCursor($cursor)
    {
        $queryString = parse_url($cursor, PHP_URL_QUERY);
        parse_str($queryString, $this->query);
        return $this->get();
    }
}
