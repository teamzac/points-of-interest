<?php

namespace TeamZac\POI\Drivers\FourSquare;

use Illuminate\Support\Arr;
use TeamZac\POI\Contracts\RetrieveQueryInterface;

class RetrieveQuery implements RetrieveQueryInterface
{
    use MapsFourSquareResults;
    
    /** @var FourSquare\Client */
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
        $json = $this->client->get("venues/{$id}");

        return $this->mapResultToPlace(Arr::get($json, 'response.venue'));
    }
    
}