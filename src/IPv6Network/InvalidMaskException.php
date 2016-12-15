<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv6Network;

class InvalidMaskException extends \InvalidArgumentException
{
    /**
     * @param int $cidr
     *
     * @return InvalidMaskException
     */
    public static function create($cidr)
    {
        return new self(sprintf('The CIDR mask must be equal 0-128. Actual CIDR mask is "%s".', $cidr));
    }
}
