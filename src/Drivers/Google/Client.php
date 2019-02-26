<?php

namespace TeamZac\POI\Drivers\Google;

use TeamZac\POI\Support\HttpClient;

class Client extends HttpClient
{
    public $baseUri = 'https://maps.googleapis.com/maps/api/place/';

    /** @var string */
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @inheritdoc
     */
    public function defaultHeaders()
    {
        return [
            'accept' => 'application/json',
        ];
    }

    /**
     * @inheritdoc
     */
    public function defaultQueryParams()
    {
        return [
            'key' => $this->apiKey,
        ];
    }
}
