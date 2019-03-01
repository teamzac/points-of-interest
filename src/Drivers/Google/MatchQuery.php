<?php

namespace TeamZac\POI\Drivers\Google;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Contracts\MatchQueryInterface;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class MatchQuery implements MatchQueryInterface
{
    use MapsGoogleResults;

    /** @var Google\Client */
    protected $client;

    /** @var array */
    protected $query = [
        'inputtype' => 'textquery',
        'fields' => 'id,name,place_id,geometry/location,formatted_address,permanently_closed,photos,types',
    ];

    /** @var TeamZac\POI\Support\Point */
    protected $latLng;

    /** @var int */
    protected $radius = 250;

    /**
     * Construct the query.
     *
     * @param   Google\Client $client
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
        if (is_numeric($term)) {
            return $this->phone($term);
        }

        $this->query['input'] = $term;
        $this->query['inputtype'] = 'textquery';

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
    public function near(Address $address)
    {
        if (! $address->hasLatLng()) {
            throw new InsufficientAddressException('Google requires a lat/lng pair for this query');
        }

        $this->latLng = $address->latLng;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function radius($radiusInMeters)
    {
        $this->radius = $radiusInMeters;

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
        $this->query['locationbias'] = sprintf(
            'circle:%s:%s,%s', 
            $this->radius,
            $this->latLng->getLat(), 
            $this->latLng->getLng()
        );

        $json = $this->client->get('findplacefromtext/json', $this->query);

        return $this->mapResultToPlace(Arr::get($json, 'candidates', [])[0]);
    }
}
