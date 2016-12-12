<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Time;

use GpsLab\Component\Interval\Exception\OutOfIntervalException;

class OutOfTimeIntervalException extends OutOfIntervalException
{
    /**
     * @param TimeIntervalPoint $point
     * @param TimeInterval $interval
     *
     * @return self
     */
    public static function create(TimeIntervalPoint $point, TimeInterval $interval)
    {
        return new static(sprintf('Time "%s" must be included in interval "%s".', $point, $interval));
    }
}
