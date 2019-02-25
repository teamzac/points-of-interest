<?php

namespace Teamzac\PointsOfInterest;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Teamzac\PointsOfInterest\Skeleton\SkeletonClass
 */
class PointsOfInterestFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'points-of-interest';
    }
}
