<?php

namespace TeamZac\POI\Drivers\Google;

use GuzzleHttp\Client;

class MatchQuery
{
    /** @var array */
    protected $query = [
        'inputtype' => 'textquery',
        'fields' => 'id,name,formatted_address,permanently_closed,photos,types',
    ];

    /**
     * Construct the query
     * 
     * @param   array $key
     */
    public function __construct($key)
    {
        $this->query['key'] = $key;
    }
    
    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function search($searchTerm)
    {
        if (is_numeric($searchTerm)) {
            return $this->phone($searchTerm);
        }

        $this->query['input'] = $searchTerm;
        $this->query['inputtype'] = 'textquery';

        return $this;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function phone($phone)
    {
        $this->query['input'] = $phone;
        $this->query['inputtype'] = 'phonenumber';

        return $this;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function near($lat, $lng)
    {
        $this->query['locationbias'] = 'point:'.$lat.','.$lng;

        return $this;
    }

    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function get()
    {
        $client = new Client([
            'base_uri' => 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json',
            'timeout'  => 10.0,
            'stream' => false,
        ]);

        $queryString =  http_build_query($this->query);

        $response = $client->get('', [
            'headers' => [
                'Accept'     => 'application/json',
            ],
            'query' => $this->query,
        ]);

        if ( $response->getStatusCode() >= 400 )
        {
            throw new \Exception('Unable to process Geocoding');
        }

        $json = json_decode($response->getBody()->getContents());

        return $json;
    }
}
