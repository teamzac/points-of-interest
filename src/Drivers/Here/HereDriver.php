<?php

namespace TeamZac\POI\Drivers\Here;

use TeamZac\POI\Contracts\ProviderInterface;

class HereDriver implements ProviderInterface
{
    /** @var array */
    protected $credentials;

    public function __construct($config)
    {
        $this->credentials = [
            'app_id' => $config['app_id'],
            'app_code' => $config['key'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function match($term = null)
    {
        return (new MatchQuery($this->hereClient()))->search($term);
    }

    /**
     * {@inheritdoc}
     */
    public function search($term = null)
    {
        return (new SearchQuery($this->hereClient()))->search($term);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($id)
    {
        return (new RetrieveQuery($this->hereClient()))->get($id);
    }

    /**
     * @param
     * @return
     */
    protected function hereClient()
    {
        return new Client($this->credentials);
    }
}
