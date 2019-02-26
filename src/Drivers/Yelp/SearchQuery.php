<?php

namespace TeamZac\POI\Drivers\Yelp;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Support\PlaceCollection;
use TeamZac\POI\Contracts\SearchQueryInterface;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class SearchQuery implements SearchQueryInterface
{
    use MapsYelpResults;

    /** @var Yelp\Client */
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
     * @param   Yelp\Client $client
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
     * @inheritdoc
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
        if (!$address->hasLatLng()) {
            throw new InsufficientAddressException('Yelp requires a lat/lng pair for this query');
        }

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
        $json = $this->client->get($this->endpointUrl, $this->buildQueryParams());

        return PlaceCollection::make($this->mapResults(Arr::get($json, 'businesses', [])))
            ->setProvider('yelp')
            ->setCursor($this->getCursor());
    }

    /**
     * Build the query params
     * 
     * @param   array $attributes
     * @return  array
     */
    protected function buildQueryParams($attributes = [])
    {
        return array_merge($attributes, $this->query);
    }

    /**
     * Build the query string, overriding any query params already set as needed
     * 
     * @param   array $attributes
     * @return  string
     */
    protected function buildQueryString($attributes = [])
    {
        return http_build_query($this->buildQueryParams($attributes));
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
     * Iterate and map results to place objects
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
