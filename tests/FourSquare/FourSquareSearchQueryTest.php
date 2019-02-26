<?php

namespace TeamZac\POI\Tests\FourSquare;

use TeamZac\POI\Facades\POI;
use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Tests\TestCase;
use TeamZac\POI\Support\Address;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class FourSquareSearchQueryTest extends TestCase
{
    /** @test */
    function a_latlng_or_street_is_required_by_foursquare()
    {
        $attributes = [
            'street' => '123 Main Street',
            'latLng' => LatLng::make(32, -97),
        ];

        foreach ($attributes as $key => $value) {
            $addressValues = $attributes;
            unset($addressValues[$key]);

            $address = Address::make($addressValues);
            $this->assertFalse($address->validate([$key]));

            try {
                POI::driver('foursquare')->search('asdf')->near($address);
            } catch (InsufficientAddressException $e) {
                continue;
            }

            $this->fail('An InsufficientAddressException should have been thrown for not having the key: ' . $key);
        }
    }
}