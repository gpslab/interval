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
            throw InvalidPointTypeException::create('IPv4', $ip);
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
