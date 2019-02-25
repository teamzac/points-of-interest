<?php

namespace TeamZac\POI\Drivers\Here;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Contracts\MatchQueryInterface;

class MatchQuery implements MatchQueryInterface
{
    use MapsHereResults;

    /** @var GuzzleHttp\Client */
    protected $client;

    /** @var array */
    protected $credentials;

    /** @var array */
    protected $query = [
        'cs' => 'places',
        'size' => 1,
        'cat' => 'eat-drink,going-out,shopping,petrol-station,accommodation,sports-facility-venue,amusement-holiday-park',
        'show_refs' => 'facebook,yelp,opentable',
    ];

    public function __construct($client, $credentials)
    {
        $this->client = $client;
        $this->credentials = $credentials;
    }

    /**
     * @inheritdoc
     */
    public function search($term = null)
    {
        $this->query['q'] = $term;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function phone($phone = null)
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
        $response = $this->client->get('autosuggest', [
            'query' => array_merge($this->query, $this->credentials),
        ]);

        if ( $response->getStatusCode() >= 400 )
        {
            throw new \Exception('Unable to process Geocoding');
        }

        $json = json_decode($response->getBody()->getContents(), true);

        return $this->mapResultToPlace(Arr::get($json, 'results.0'));
    }
}