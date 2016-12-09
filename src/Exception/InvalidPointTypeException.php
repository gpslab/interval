<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Exception;

class InvalidPointTypeException extends \InvalidArgumentException
{
    /**
     * @param string $expected_type
     * @param mixed $point
     *
     * @return self
     */
    public static function create($expected_type, $point)
    {
        return new static(sprintf(
            'The point value must be of "%s" type. Actual type is "%s".',
            $expected_type,
            gettype($point)
        ));
    }
}
