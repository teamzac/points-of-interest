<?php

namespace TeamZac\POI\Support;

use GuzzleHttp\Client;

class HttpClient
{
    /**
     * Perform a GET request.
     *
     * @param   string $endpoint
     * @param   array $queryParams
     * @return  array
     * @throws  Exception
     */
    public function get($endpoint, $queryParams = [])
    {
        $response = $this->httpClient()->get($endpoint, [
            'query' => http_build_query(array_merge($queryParams, $this->defaultQueryParams())),
        ]);

        if ($response->getStatusCode() >= 400) {
            throw new \Exception('Unable to process POI query');
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get the underlying Guzzle client.
     *
     * @return  GuzzleHttp\Client
     */
    protected function httpClient()
    {
        return new Client([
            'base_uri' => $this->baseUri(),
            'timeout'  => 10.0,
            'stream' => false,
            'headers' => $this->defaultHeaders(),
            'debug' => false,
        ]);
    }

    /**
     * Get the base URI.
     *
     * @return  string
     */
    public function baseUri()
    {
        return $this->baseUri;
    }

    /**
     * Return some default values to be merged into the headers.
     *
     * @return  array
     */
    public function defaultHeaders()
    {
        return [];
    }

    /**
     * Return some default values to be merged into the query parameters.
     *
     * @return  array
     */
    public function defaultQueryParams()
    {
        return [];
    }
}
