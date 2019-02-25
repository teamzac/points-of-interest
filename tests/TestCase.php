<?php

namespace TeamZac\POI\Tests;

use TeamZac\POI\Facades\POI;
use Orchestra\Testbench\TestCase as BaseTest;
use TeamZac\POI\PointsOfInterestServiceProvider;

class TestCase extends BaseTest
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