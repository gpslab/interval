<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv4;

use GpsLab\Component\Interval\Exception\OutOfIntervalException;

class OutOfIPv4IntervalException extends OutOfIntervalException
{
    /**
     * @param IPv4IntervalPoint $point
     * @param IPv4Interval $interval
     *
     * @return self
     */
    public static function create(IPv4IntervalPoint $point, IPv4Interval $interval)
    {
        return new static(sprintf('IPv4 "%s" must be included in interval "%s".', $point, $interval));
    }
}
