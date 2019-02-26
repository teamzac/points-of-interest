<?php

namespace TeamZac\POI\Drivers\Google;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Support\PlaceCollection;
use TeamZac\POI\Contracts\SearchQueryInterface;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class SearchQuery implements SearchQueryInterface
{
    use MapsGoogleResults;
    
    /** @var Google\Client */
    protected $client;

    /** @var array */
    protected $query = [
        'fields' => 'id,name,place_id,formatted_address,permanently_closed,photos,types',
    ];

    /**
     * Construct the query
     * 
     * @param   GoogleClient $client
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
        $this->query['keyword'] = $term;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function near(Address $address)
    {
        if (!$address->hasLatLng()) {
            throw new InsufficientAddressException('Google requires a lat/lng pair for this query');
        }

        $this->query['location'] = $address->latLng->getDescription();
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
        $json = $this->client->get('nearbysearch/json', $this->query);

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
