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
                'formatted' => implode(', ', Arr::get($result, 'location.formattedAddress', [])),
                'latLng' => new LatLng(
                    Arr::get($result, 'location.lat'), Arr::get($result, 'location.lng')
                ),
            ]),
            'phone' => Arr::get($result, 'contact.phone'),
            'categories' => collect(Arr::get($result, 'categories', []))->map(function ($category) {
                return Str::slug(Arr::get($category, 'shortName'));
            })->toArray(),
            'extra' => [
                'chain' => $this->getChain($result),
                'url' => Arr::get($result, 'url'),
                'pageUrl' => Arr::get($result, 'canonical_url'),
                'hereNow' => Arr::get($result, 'hereNow.count', 0),
                'verified' => (bool) Arr::get($result, 'verified'),
                'rating' => Arr::get($result, 'rating'),
                'likes' => Arr::get($result, 'likes.count'),
                'price' => [
                    'symbol' => Arr::get($result, 'price.currency'),
                    'description' => Arr::get($result, 'price.message'),
                ],
                'hours' => Arr::get($result, 'hours.timeframes'),
            ],
        ]);
    }

    /**
     * Return the chain information if it exists.
     *
     * @param $result
     * @return array|null
     */
    protected function getChain($result)
    {
        if (! $pageUser = Arr::get($result, 'page.user')) {
            return;
        }

        if ($pageUser['type'] == 'chain') {
            return [
                'id' => $pageUser['id'],
                'name' => $pageUser['firstName'],
            ];
        }
    }
}
