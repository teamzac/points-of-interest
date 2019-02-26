<?php

namespace TeamZac\POI\Drivers\FourSquare;

use TeamZac\POI\Support\HttpClient;

class Client extends HttpClient
{
    public $baseUri = 'https://api.foursquare.com/v2/';

    /** @var array */
    protected $credentials;

    /** @var string */
    protected $apiVersion = '20190225';

    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultQueryParams()
    {
        return array_merge($this->credentials, [
            'v' => $this->apiVersion,
        ]);
    }
}
