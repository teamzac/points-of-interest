<?php

namespace TeamZac\POI\Drivers\Here;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Contracts\MatchQueryInterface;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class MatchQuery implements MatchQueryInterface
{
    use MapsHereResults;

    /** @var Here\Client */
    protected $client;

    /** @var array */
    protected $query = [
        'cs' => 'places',
        'size' => 1,
        // 'cat' => 'eat-drink,going-out,shopping,petrol-station,accommodation,sports-facility-venue,amusement-holiday-park',
        'show_refs' => 'facebook,yelp,opentable',
    ];

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function search($term = null)
    {
        $this->query['q'] = $term;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function phone($phone = null)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function near(Address $address, $radiusInMeters = null)
    {
        if (! $address->hasLatLng()) {
            throw new InsufficientAddressException('Here requires a lat/lng pair for this query');
        }

        $this->query['at'] = $address->latLng->getDescription();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function radius($radiusInMeters)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function within($geometry)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $json = $this->client->get('discover/search', $this->query);

        return $this->mapResultToPlace(Arr::get($json, 'results.items.0'));
    }
}
