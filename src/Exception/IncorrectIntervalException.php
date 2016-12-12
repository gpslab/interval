<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Exception;

class IncorrectIntervalException extends \RangeException
{
    /**
     * @return IncorrectIntervalException
     */
    public static function create()
    {
        return new self('The start of interval must be less than end of it.');
    }
}
