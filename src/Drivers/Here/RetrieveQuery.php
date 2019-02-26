<?php

namespace TeamZac\POI\Drivers\Here;

use TeamZac\POI\Contracts\RetrieveQueryInterface;

class RetrieveQuery implements RetrieveQueryInterface
{
    use MapsHereResults;

    /** @var Here\Client */
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        $json = $this->client->get('places/lookup', [
            'source' => 'sharing',
            'id' => $id,
            'show_refs' => 'facebook,yelp,opentable',
        ]);

        return $this->mapResultToPlace($json);
    }
}
