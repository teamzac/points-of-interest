<?php

namespace TeamZac\POI\Drivers\FourSquare;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;

trait MapsFourSquareResults
{
    /**
     * @param
     * @return
     */
    public function mapResultToPlace($result)
    {
        return (new Place)->setRaw($result)->map([
            'provider' => 'foursquare',
            'id' => Arr::get($result, 'id'),
            'name' => Arr::get($result, 'name'),
            'address' => new Address([
                'street' => Arr::get($result, 'location.address'),
                'city' => Arr::get($result, 'location.city'),
                'state' => Arr::get($result, 'location.state'),
                'postalCode' => Arr::get($result, 'location.postalCode'),
                'country' => Arr::get($result, 'location.country'),
                'formatted' => implode(', ', Arr::get($result, 'location.formattedAddress')),
                'latLng' => new LatLng(
                    Arr::get($result, 'location.lat'), Arr::get($result, 'location.lng')
                ),
            ]),
            'phone' => Arr::get($result, 'contact.phone'),
            'categories' => collect(Arr::get($result, 'categories', []))->map(function ($category) {
                return Str::slug(Arr::get($category, 'shortName'));
            })->toArray(),
        ]);
    }
}
