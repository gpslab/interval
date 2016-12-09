<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\date;

use GpsLab\Component\Interval\Exception\OutOfIntervalException;

class OutOfDateIntervalException extends OutOfIntervalException
{
    /**
     * @param DateIntervalPoint $point
     * @param DateInterval $interval
     *
     * @return self
     */
    public static function create(DateIntervalPoint $point, DateInterval $interval)
    {
        return new static(sprintf('Date "%s" must be included in interval "%s".', $point, $interval));
    }
}
