<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv6;

use GpsLab\Component\Interval\Exception\InvalidPointTypeException;
use GpsLab\Component\Interval\BaseIntervalPoint;
use GpsLab\Component\Interval\IPv4\IPv4IntervalPoint;

class IPv6IntervalPoint extends BaseIntervalPoint
{
    /**
     * @var string
     */
    const IPV6_SEG = '[0-9a-fA-F]{1,4}';

    /**
     * IPv6 address
     *
     * Ignore:
     *     link-local IPv6 addresses with zone index
     *
     * @see http://vernon.mauery.com/content/projects/linux/ipv6_regex
     *
     * @var string
     */
    const IPV6_ADDR = '(
           ('.self::IPV6_SEG.':){7,7}'.self::IPV6_SEG.'|         # 1:2:3:4:5:6:7:8
           ('.self::IPV6_SEG.':){1,7}:|                          # 1::                                 1:2:3:4:5:6:7::
           ('.self::IPV6_SEG.':){1,6}:'.self::IPV6_SEG.'|        # 1::8               1:2:3:4:5:6::8   1:2:3:4:5:6::8
           ('.self::IPV6_SEG.':){1,5}(:'.self::IPV6_SEG.'){1,2}| # 1::7:8             1:2:3:4:5::7:8   1:2:3:4:5::8
           ('.self::IPV6_SEG.':){1,4}(:'.self::IPV6_SEG.'){1,3}| # 1::6:7:8           1:2:3:4::6:7:8   1:2:3:4::8
           ('.self::IPV6_SEG.':){1,3}(:'.self::IPV6_SEG.'){1,4}| # 1::5:6:7:8         1:2:3::5:6:7:8   1:2:3::8
           ('.self::IPV6_SEG.':){1,2}(:'.self::IPV6_SEG.'){1,5}| # 1::4:5:6:7:8       1:2::4:5:6:7:8   1:2::8
           '.self::IPV6_SEG.':((:'.self::IPV6_SEG.'){1,6})|      # 1::3:4:5:6:7:8     1::3:4:5:6:7:8   1::8
           :((:'.self::IPV6_SEG.'){1,7}|:)|                      # ::2:3:4:5:6:7:8    ::2:3:4:5:6:7:8  ::8       ::

           # link-local IPv6 addresses with zone index
           #fe80:(:'.self::IPV6_SEG.'){0,4}%[0-9a-zA-Z]{1,}|      # fe80::7:8%eth0     fe80::7:8%1

           # IPv4-mapped IPv6 addresses and IPv4-translated addresses
           # ::255.255.255.255  ::ffff:255.255.255.255  ::ffff:0:255.255.255.255
           ::(ffff(:0{1,4}){0,1}:){0,1}'.IPv4IntervalPoint::IPV4_ADDR.'|

           # IPv4-Embedded IPv6 Address
           # 2001:db8:3:4::192.0.2.33  64:ff9b::192.0.2.33
           ('.self::IPV6_SEG.':){1,4}:'.IPv4IntervalPoint::IPV4_ADDR.'
       )';

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
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            throw InvalidPointTypeException::point('IPv6', $ip);
        }

        $this->ip = $ip;
        $this->long = inet_pton($ip);
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
