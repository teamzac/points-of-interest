<?php

use TeamZac\POI\Facades\POI;
use TeamZac\POI\Tests\TestCase;
use TeamZac\POI\Support\Address;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class YelpMatchQueryTest extends TestCase
{
    /** @test */
    function full_address_is_required()
    {
        $query = POI::driver('yelp')->match('asdf')
            ->near(Address::make([
                'street' => '123 main',
            ]));

        try {
            $query->get();
        } catch (InsufficientAddressException $e) {
            return;
        }

        $this->fail('An InsufficientAddressException should have been thrown');
    }
}