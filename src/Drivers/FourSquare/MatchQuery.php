<?php

namespace TeamZac\POI\Drivers\FourSquare;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Contracts\MatchQueryInterface;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class MatchQuery implements MatchQueryInterface
{
    use MapsFourSquareResults;

    /** @var FourSquare\Client */
    protected $client;

    /** @var array */
    protected $query = [
        'intent' => 'match',
        'radius' => 250,
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
        $this->query['query'] = $term;

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
        if (! $address->hasLatLng() || ! $address->validate(['street'])) {
            throw new InsufficientAddressException('FourSquare requires a lat/lng pair and/or street number for this query');
        }

        $this->query['ll'] = $address->latLng->getDescription();
        $this->query['address'] = $address->street;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function radius($radiusInMeters)
    {
        $this->query['radius'] = $radius;

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
        $json = $this->client->get('venues/search', $this->query);

        return $this->mapResultToPlace(Arr::get($json, 'response.venues.0'));
    }
}
