<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Year;

use GpsLab\Component\Interval\Exception\OutOfIntervalException;

class OutOfYearIntervalException extends OutOfIntervalException
{
    /**
     * @param YearIntervalPoint $point
     * @param YearInterval $interval
     *
     * @return self
     */
    public static function create(YearIntervalPoint $point, YearInterval $interval)
    {
        return new static(sprintf('Year "%s" must be included in interval "%s".', $point, $interval));
    }
}
