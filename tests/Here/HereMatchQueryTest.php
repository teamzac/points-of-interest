<?php

namespace TeamZac\POI\Tests\Here;

use TeamZac\POI\Facades\POI;
use TeamZac\POI\Tests\TestCase;
use TeamZac\POI\Support\Address;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class HereMatchQueryTest extends TestCase
{
    /** @test */
    function lat_lng_are_required()
    {
        $address = Address::make();
        $this->assertFalse($address->hasLatLng());

        try {
            POI::driver('here')->match('asdf')->near($address);
        } catch (\Exception $e) {
            return;
        }

        $this->fail('An InsufficientAddressException should have been thrown');
    }
}