<?php

namespace TeamZac\POI\Drivers\Yelp;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Contracts\MatchQueryInterface;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class MatchQuery implements MatchQueryInterface
{
    use MapsYelpResults;

    /** @var Yelp\Client */
    protected $client;

    /** @var array */
    protected $query = [];

    /**
     * Construct the query.
     *
     * @param   $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function search($term = null)
    {
        $this->query['name'] = $term;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function phone($phone = null)
    {
        $this->query['input'] = $phone;
        $this->query['inputtype'] = 'phonenumber';

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function near(Address $address, $radiusInMeters = null)
    {
        if (! $address->validate(['street', 'city', 'state', 'country', 'postalCode'])) {
            throw new InsufficientAddressException('Yelp requires a street, city, state, country, and postal code for this query');
        }

        $this->query = array_merge([
            'address1' => $address->street,
            'city' => $address->city,
            'state' => $address->state,
            'country' => $address->country,
            'zip_code' => $address->postalCode,
        ], $this->query);

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
        $json = $this->client->get('matches', $this->query);

        return $this->mapResultToPlace(Arr::get($json, 'businesses.0'));
    }
}
