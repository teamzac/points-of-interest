<?php

namespace TeamZac\POI\Drivers\Google;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;

trait MapsGoogleResults
{
    /**
     * 
     * 
     * @param   
     * @return  
     */
    public function mapResultToPlace($result)
    {
        return (new Place)->setRaw($result)->map([
            'provider' => 'google',
            'id' => Arr::get($result, 'place_id'),
            'name' => Arr::get($result, 'name'),
            'address' => new Address([
                'formatted' => Arr::get($result, 'formatted_address'),
                'latLng' => new LatLng(
                    Arr::get($result, 'geometry.location.lat'), Arr::get($result, 'geometry.location.lng')
                ),
            ]),
            'categories' => Arr::get($result, 'types', [])
        ]);
    }
}