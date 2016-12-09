<?php
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Exception;

class InvalidIntervalFormatException extends \InvalidArgumentException
{
    /**
     * @param string $expected
     * @param string $actual
     *
     * @return self
     */
    public static function create($expected, $actual)
    {
        return new self(sprintf(
            'The example of expected interval format is "%s". Actual format is "%s".',
            $expected,
            $actual
        ));
    }
}
