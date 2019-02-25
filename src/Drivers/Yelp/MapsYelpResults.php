<?php

namespace TeamZac\POI\Drivers\Yelp;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;

trait MapsYelpResults
{
    /**
     * Map an API result to a Place object
     * 
     * @param   array $result
     * @return  TeamZac\POI\Support\Place
     */
    public function mapResultToPlace($result)
    {
        return (new Place)->setRaw($result)->map([
            'provider' => 'yelp',
            'id' => Arr::get($result, 'id'),
            'name' => Arr::get($result, 'name'),
            'address' => new Address([
                'street' => Arr::get($result, 'location.address1'),
                'city' => Arr::get($result, 'location.city'),
                'state' => Arr::get($result, 'location.state'),
                'postalCode' => Arr::get($result, 'location.zip_code'),
                'country' => Arr::get($result, 'location.country'),
                'formatted' => implode(', ', Arr::get($result, 'location.display_address')),
                'latLng' => new LatLng(
                    Arr::get($result, 'coordinates.latitude'), Arr::get($result, 'coordinates.longitude')
                ),
            ]),
            'phone' => Arr::get($result, 'display_phone'),
            'categories' => collect(Arr::get($result, 'categories', []))->map(function($category) {
                return $category['alias'];
            })->toArray(),
        ]);
    }
}