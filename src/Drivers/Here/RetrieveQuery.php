<?php

namespace TeamZac\POI\Drivers\Here;

use TeamZac\POI\Contracts\RetrieveQueryInterface;

class RetrieveQuery implements RetrieveQueryInterface
{
    use MapsHereResults;

    /** @var GuzzleHttp\Client */
    protected $client;

    /** @var array */
    protected $credentials;

    public function __construct($client, $credentials)
    {
        $this->client = $client;
        $this->credentials = $credentials;
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $response = $this->client->get('places/lookup', [
            'query' => array_merge([
                'source' => 'sharing',
                'id' => $id,
                'show_refs' => 'facebook,yelp,opentable',
            ], $this->credentials)
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        return $this->mapResultToPlace($json);
    }
}