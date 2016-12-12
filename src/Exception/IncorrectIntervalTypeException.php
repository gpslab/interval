<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Exception;

class IncorrectIntervalTypeException extends \OutOfRangeException
{
    /**
     * @param string $value
     *
     * @return self
     */
    public static function create($value)
    {
        return new self(sprintf('Value "%s" is not supported as interval type.', $value));
    }
}
