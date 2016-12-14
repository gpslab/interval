<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv4Network;

class InvalidMaskException extends \InvalidArgumentException
{
    /**
     * @param int $cidr
     *
     * @return InvalidMaskException
     */
    public static function cidr($cidr)
    {
        return new self(sprintf('The CIDR mask must be equal 0-32. Actual CIDR mask is "%s".', $cidr));
    }

    /**
     * @param string $ip
     *
     * @return self
     */
    public static function ip($ip)
    {
        return new self(sprintf('The example of expected IP mask is "255.255.255.0". Actual IP mask is "%s".', $ip));
    }
}
