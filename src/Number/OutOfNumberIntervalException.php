<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Number;

use GpsLab\Component\Interval\Exception\OutOfIntervalException;

class OutOfNumberIntervalException extends OutOfIntervalException
{
    /**
     * @param NumberIntervalPoint $point
     * @param NumberInterval $interval
     *
     * @return self
     */
    public static function create(NumberIntervalPoint $point, NumberInterval $interval)
    {
        return new static(sprintf('Number "%s" must be included in interval "%s".', $point, $interval));
    }
}
