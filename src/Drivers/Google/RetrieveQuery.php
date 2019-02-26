<?php

namespace TeamZac\POI\Drivers\Google;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Contracts\RetrieveQueryInterface;

class RetrieveQuery implements RetrieveQueryInterface
{
    use MapsGoogleResults;
    
    /** @var Google\Client */
    protected $client;

    public function __construct($client) 
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $json = $this->client->get('details/json', [
            'placeid' => $id,
            'fields' => 'id,name,place_id,geometry/location,formatted_address,permanently_closed,types',
        ]);

        return $this->mapResultToPlace(Arr::get($json, 'result', []));
    }
}