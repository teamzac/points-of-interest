<?php

namespace TeamZac\POI\Drivers\FourSquare;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Support\PlaceCollection;
use TeamZac\POI\Contracts\SearchQueryInterface;

class SearchQuery implements SearchQueryInterface
{
    use MapsFourSquareResults;
    
    /** @var FourSquare\Client */
    protected $client;

    /** @var array */
    protected $query = [
        'intent' => 'checkin',
        'radius' => 1000
    ];

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function search($term = null)
    {
        $this->query['query'] = $term;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function near(Address $address)
    {
        if (!$address->hasLatLng() || !$address->validate(['street'])) {
            throw new InsufficientAddressException('FourSquare requires a lat/lng pair and/or street number for this query');
        }

        $this->query['ll'] = $address->latLng->getDescription();
        $this->query['address'] = $address->street;

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
        $json = $this->client->get('venues/search', $this->query);

        return PlaceCollection::make($this->mapResults(Arr::get($json, 'response.venues')))
            ->setProvider('foursquare');
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
        return $this;
    }
}