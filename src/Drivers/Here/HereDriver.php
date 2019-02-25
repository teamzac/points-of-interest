<?php

namespace TeamZac\POI\Drivers\Here;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\HandlerStack;
use TeamZac\POI\Contracts\ProviderInterface;

class HereDriver implements ProviderInterface
{
    /** @var array */
    protected $credentials;

    public function __construct($config) 
    {
        $this->credentials = [
            'app_id' => $config['app_id'],
            'app_code' => $config['key']
        ];
    }

    /**
     * @inheritdoc
     */
    public function match($term = null)
    {
        return (new MatchQuery($this->hereClient(), $this->credentials))->search($term);
    }

    /**
     * @inheritdoc
     */
    public function search($term = null)
    {
        return (new SearchQuery($this->hereClient(), $this->credentials))->search($term);
    }

    /**
     * @inheritdoc
     */
    public function retrieve($id)
    {
        return (new RetrieveQuery($this->hereClient(), $this->credentials))->get($id);
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    protected function hereClient()
    {
        return new Client([
            'base_uri' => 'https://places.cit.api.here.com/places/v1/',
            'timeout'  => 10.0,
            'stream' => false,
            'headers' => [
                'Accept'     => 'application/json',
            ]
        ]);
    }
}