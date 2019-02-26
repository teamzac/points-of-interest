<?php

namespace TeamZac\POI\Drivers\Here;

use TeamZac\POI\Support\HttpClient;

class Client extends HttpClient
{
    public $baseUri = 'https://places.cit.api.here.com/places/v1/';

    /** @var array */
    protected $credentials;

    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultHeaders()
    {
        return [
            'Accept'     => 'application/json',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function defaultQueryParams()
    {
        return $this->credentials;
    }
}
