<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Month;

use GpsLab\Component\Interval\Exception\OutOfIntervalException;

class OutOfMonthIntervalException extends OutOfIntervalException
{
    /**
     * @param MonthIntervalPoint $point
     * @param MonthInterval $interval
     *
     * @return self
     */
    public static function create(MonthIntervalPoint $point, MonthInterval $interval)
    {
        return new static(sprintf('Month "%s" must be included in interval "%s".', $point, $interval));
    }
}
