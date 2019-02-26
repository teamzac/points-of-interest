<?php

namespace TeamZac\POI\Drivers\Here;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Support\PlaceCollection;
use TeamZac\POI\Drivers\Here\MapsHereResults;
use TeamZac\POI\Contracts\SearchQueryInterface;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class SearchQuery implements SearchQueryInterface
{
    use MapsHereResults;

    /** @var Here\Client */
    protected $client;

    /** @var string */
    protected $cursorUrl;

    /** @var array */
    protected $query = [
        'show_refs' => 'facebook,yelp,opentable',
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
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function near(Address $address)
    {
        if (!$address->hasLatLng()) {
            throw new InsufficientAddressException('Here requires a lat/lng pair for this query');
        }

        $this->query['at'] = $address->latLng->getDescription();
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
        $json = $this->cursorUrl ?
            $this->client->get($this->cursorUrl) :
            $this->client->get('discover/explore', $this->query);

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

    /**
     * @inheritdoc
     */
    public function fromCursor($cursor)
    {
        $this->cursorUrl = $cursor;

        return $this->get();
    }
}