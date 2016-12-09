<?php
/**
 * Pkvs package
 *
 * @package Pkvs
 * @author  Peter Gribanov <pgribanov@1tv.com>
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
