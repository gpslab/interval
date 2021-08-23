<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Exception;

use GpsLab\Component\Interval\IntervalInterface;

class OutOfIntervalException extends \OutOfRangeException
{
    /**
     * @param mixed $point
     *
     * @return self
     */
    public static function create($point, IntervalInterface $interval)
    {
        return new static(sprintf('Point "%s" must be included in interval "%s".', $point, $interval));
    }
}
