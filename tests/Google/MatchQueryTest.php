<?php

use TeamZac\POI\Facades\POI;
use TeamZac\POI\Tests\TestCase;
use TeamZac\POI\Support\Address;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use TeamZac\POI\Exceptions\InsufficientAddressException;

class MatchQueryTest extends TestCase
{
    /** @test */
    function lat_lng_are_required()
    {
        $query = POI::driver('google')->match('asdf')
            ->near(Address::make());

        try {
            $query->get();
        } catch (InsufficientAddressException $e) {
            return;
        }

        $this->fail('An InsufficientAddressException should have been thrown');
    }
}