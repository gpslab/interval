<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv4Network;

use GpsLab\Component\Interval\IPv4\IPv4IntervalPoint;

class IPv4NetworkPoint extends IPv4IntervalPoint
{
    /**
     * @var string
     */
    const IPV4_ADDR_COMPACT = '('.self::IPV4_SEG.'\.){0,3}'.self::IPV4_SEG;
}
