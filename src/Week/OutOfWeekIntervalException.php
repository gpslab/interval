<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Week;

use GpsLab\Component\Interval\Exception\OutOfIntervalException;

class OutOfWeekIntervalException extends OutOfIntervalException
{
    /**
     * @param WeekIntervalPoint $point
     * @param WeekInterval $interval
     *
     * @return self
     */
    public static function create(WeekIntervalPoint $point, WeekInterval $interval)
    {
        return new static(sprintf('Week "%s" must be included in interval "%s".', $point, $interval));
    }
}
