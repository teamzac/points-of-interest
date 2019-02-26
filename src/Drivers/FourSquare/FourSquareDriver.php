<?php

namespace TeamZac\POI\Drivers\FourSquare;

use TeamZac\POI\Contracts\ProviderInterface;

class FourSquareDriver implements ProviderInterface
{
    /** @var array */
    protected $credentials;

    /**
     * Construct the driver
     * 
     * @param   array $config
     */
    public function __construct($config)
    {
        $this->credentials = [
            'client_id' => $config['app_id'],
            'client_secret' => $config['key'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function retrieve($id) 
    {
        return (new RetrieveQuery($this->client()))->get($id);
    }

    /**
     * @inheritdoc
     */
    public function match($term = null)
    {
        return (new MatchQuery($this->client()))->search($term);
    }

    /**
     * @inheritdoc
     */
    public function search($term = null)
    {
        return (new SearchQuery($this->client()))->search($term);
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function client()
    {
        return new Client($this->credentials);
    }
    
}