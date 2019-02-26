<?php

namespace TeamZac\POI\Drivers\Yelp;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Contracts\RetrieveQueryInterface;

class RetrieveQuery implements RetrieveQueryInterface
{
    use MapsYelpResults;
    
    /** @var Yelp\Client */
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
        $json = $this->client->get($id);

        return $this->mapResultToPlace($json);
    }

}