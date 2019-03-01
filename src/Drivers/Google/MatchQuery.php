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
    public function near(Address $address, $radiusInMeters = null)
    {
        if (! $address->hasLatLng()) {
            throw new InsufficientAddressException('Google requires a lat/lng pair for this query');
        }

        $this->query['locationbias'] = sprintf('point:%s,%s', $address->latLng->getLat(), $address->latLng->getLng());

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
        $json = $this->client->get('findplacefromtext/json', $this->query);

        return $this->mapResultToPlace(Arr::get($json, 'candidates', [])[0]);
    }
}
