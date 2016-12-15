<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv4;

use GpsLab\Component\Interval\Exception\InvalidPointTypeException;
use GpsLab\Component\Interval\BaseIntervalPoint;

class IPv4IntervalPoint extends BaseIntervalPoint
{
    /**
     * @var string
     */
    const IPV4_SEG = '(25[0-5]|(2[0-4]|1?[0-9])?[0-9])';

    /**
     * @var string
     */
    const IPV4_ADDR = '('.self::IPV4_SEG.'\.){3,3}'.self::IPV4_SEG;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var int
     */
    private $long;

    /**
     * @param string $ip
     */
    public function __construct($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            throw InvalidPointTypeException::point('IPv4', $ip);
        }

        $this->ip = $ip;
        $this->long = ip2long($ip);
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->long;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->ip;
    }
}
