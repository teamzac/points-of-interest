<?php

namespace TeamZac\POI\Tests\Yelp;

use TeamZac\POI\Facades\POI;
use TeamZac\POI\Tests\TestCase;
use TeamZac\POI\Support\Address;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class YelpSearchQueryTest extends TestCase
{
    /** @test */
    function a_full_address_is_required_by_yelp()
    {
        $address = Address::make();
        $this->assertFalse($address->hasLatLng());

        try {
            POI::driver('yelp')->search('asdf')->near($address);
        } catch (\Exception $e) {
            return;
        }

        $this->fail('An InsufficientAddressException should have been thrown');
    }
}