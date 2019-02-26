<?php

namespace TeamZac\POI\Tests\Yelp;

use TeamZac\POI\Facades\POI;
use TeamZac\POI\Tests\TestCase;
use TeamZac\POI\Support\Address;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class YelpMatchQueryTest extends TestCase
{
    /** @test */
    public function a_full_address_is_required_by_yelp()
    {
        $attributes = [
            'street' => '123 Main Street',
            'city' => 'Fort Worth',
            'state' => 'Texas',
            'postalCode' => 76102,
            'country' => 'US',
        ];

        foreach ($attributes as $key => $value) {
            $addressValues = $attributes;
            unset($addressValues[$key]);

            $address = Address::make($addressValues);
            $this->assertFalse($address->validate([$key]));

            try {
                POI::driver('yelp')->match('asdf')->near($address);
            } catch (InsufficientAddressException $e) {
                continue;
            }

            $this->fail('An InsufficientAddressException should have been thrown for not having the key: '.$key);
        }
    }
}
