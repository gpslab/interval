<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\DateTime;

use GpsLab\Component\Interval\Exception\OutOfIntervalException;

class OutOfDateTimeIntervalException extends OutOfIntervalException
{
    /**
     * @param DateTimeIntervalPoint $point
     * @param DateTimeInterval $interval
     *
     * @return self
     */
    public static function create(DateTimeIntervalPoint $point, DateTimeInterval $interval)
    {
        return new static(sprintf('DateTime "%s" must be included in interval "%s".', $point, $interval));
    }
}
