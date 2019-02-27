<?php

namespace TeamZac\POI\Tests\Support;

use TeamZac\POI\Support\LatLng;
use TeamZac\POI\Tests\TestCase;
use TeamZac\POI\Support\BoundingBox;

class BoundingBoxTest extends TestCase
{
    /** @test */
    function it_provides_nw_and_se_corners()
    {
        $sw = LatLng::make(0, 0);
        $ne = LatLng::make(5, 5);
        // $nw = LatLng::make(5, 0);
        // $se = LatLng::make(0, 5);
        $box = BoundingBox::fromSouthWest($sw, $ne);

        $nw = $box->nw();
        $this->assertEquals(5, $nw->getLat());
        $this->assertEquals(0, $nw->getLng());

        $se = $box->se();
        $this->assertEquals(0, $se->getLat());
        $this->assertEquals(5, $se->getLng());
    }

    /** @test */
    function it_provides_ne_and_sw_corners()
    {
        // $sw = LatLng::make(0, 0);
        // $ne = LatLng::make(5, 5);
        $nw = LatLng::make(5, 0);
        $se = LatLng::make(0, 5);
        $box = BoundingBox::fromNorthWest($nw, $se);

        $ne = $box->ne();
        $this->assertEquals(5, $ne->getLat());
        $this->assertEquals(5, $ne->getLng());

        $sw = $box->sw();
        $this->assertEquals(0, $sw->getLat());
        $this->assertEquals(0, $sw->getLng());
    }

    /** @test */
    function an_exception_is_thrown_if_not_enough_information_is_provided()
    {
        $box = new BoundingBox;

        $exceptionsThrown = 0;

        try {
            $box->nw();
        } catch (\Exception $e) {
            $exceptionsThrown++;
        }

        try {
            $box->ne();
        } catch (\Exception $e) {
            $exceptionsThrown++;
        }

        try {
            $box->se();
        } catch (\Exception $e) {
            $exceptionsThrown++;
        }

        try {
            $box->sw();
        } catch (\Exception $e) {
            $exceptionsThrown++;
        }

        $this->assertEquals(4, $exceptionsThrown);
    }
}