<?php

namespace TeamZac\POI\Drivers\Google;

use Illuminate\Support\Arr;
use TeamZac\POI\Support\Place;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Support\Address;

trait MapsGoogleResults
{
    /**
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
                'formatted' => Arr::get($result, 'formatted_address') ?? Arr::get($result, 'vicinity'),
                'latLng' => new LatLng(
                    Arr::get($result, 'geometry.location.lat'), Arr::get($result, 'geometry.location.lng')
                ),
            ]),
            'categories' => Arr::get($result, 'types', []),
            'phone' => Arr::get($result, 'formatted_phone_number'),
            'extra' => [
                // url
                'pageUrl' => Arr::get($result, 'url'),
                // here now
                // verified
                'rating' => Arr::get($result, 'rating'),
                'rating_count' => Arr::get($result, 'user_ratings_total'),
                'hours' => Arr::get($result, 'opening_hours', null),
            ],
        ]);
    }
}
