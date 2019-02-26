<?php

namespace TeamZac\POI\Drivers\Google;

use TeamZac\POI\Contracts\ProviderInterface;

class GoogleDriver implements ProviderInterface
{
    /** @var string */
    protected $apiKey;

    /**
     * Construct the driver.
     *
     * @param   array $config
     */
    public function __construct($config)
    {
        $this->apiKey = $config['key'];
    }

    /**
     * {@inheritdoc}
     */
    public function match($term = null)
    {
        return (new MatchQuery($this->googleClient()))->search($term);
    }

    /**
     * {@inheritdoc}
     */
    public function search($term = null)
    {
        return (new SearchQuery($this->googleClient()))->search($term);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($id)
    {
        return (new RetrieveQuery($this->googleClient()))->get($id);
    }

    /**
     * @param
     * @return
     */
    protected function googleClient()
    {
        return new Client($this->apiKey);
    }
}
