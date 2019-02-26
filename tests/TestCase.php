<?php

namespace TeamZac\POI\Tests;

use TeamZac\POI\Facades\POI;
use TeamZac\POI\PointsOfInterestServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PointsOfInterestServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'POI' => POI::class,
        ];
    }
}