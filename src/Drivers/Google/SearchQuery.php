<?php

namespace TeamZac\POI\Drivers\Google;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Support\PlaceCollection;
use TeamZac\POI\Contracts\SearchQueryInterface;

class SearchQuery implements SearchQueryInterface
{
    use MapsGoogleResults;
    
    /** @var GuzzleHttp\Client */
    protected $client;

    /** @var array */
    protected $query = [
        'fields' => 'id,name,place_id,formatted_address,permanently_closed,photos,types',
    ];

    /**
     * Construct the query
     * 
     * @param   array $key
     */
    public function __construct($client, $key)
    {
        $this->client = $client;
        $this->query['key'] = $key;
    }
    
    /**
     * @inheritdoc
     */
    public function search($term = null)
    {
        $this->query['keyword'] = $term;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function near(Address $address)
    {
        $this->query['location'] = sprintf('%s,%s', $address->latLng->getLat(), $address->latLng->getLng());
        $this->query['rankby'] = 'distance';

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function radius($meters)
    {
        $this->query['radius'] = $meters;
        $this->query['rankby'] = 'prominence';

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
     * @inheritdoc
     */
    public function get()
    {
        $response = $this->client->get('nearbysearch/json', [
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

    /**
     * @inheritdoc
     */
    public function fromCursor($cursor)
    {
        $this->query = [
            'pagetoken' => $cursor,
        ];

        return $this->get();
    }
}
