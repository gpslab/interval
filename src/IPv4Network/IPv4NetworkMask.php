<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv4Network;

use GpsLab\Component\Interval\Exception\InvalidPointTypeException;

class IPv4NetworkMask
{
    /**
     * @var string
     */
    const CIDR = '(3[0-2]|[1-2]?[0-9])'; // 0-32

    /**
     * @var string
     */
    private $ip = '';

    /**
     * @var int
     */
    private $long = 0;

    /**
     * @var int
     */
    private $cidr = 0;

    /**
     * @param string $ip
     * @param int $cidr
     */
    private function __construct($ip, $cidr)
    {
        $this->ip = $ip;
        $this->long = ip2long($ip);
        $this->cidr = $cidr;
    }

    /**
     * @param string $ip
     *
     * @return self
     */
    public static function fromIP($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            throw InvalidPointTypeException::point('IPv4', $ip);
        }

        // get CIDR from IP mask
        $mask = '';
        foreach (explode('.', $ip) as $octet) {
            $mask .= str_pad(decbin($octet), 8, '0', STR_PAD_LEFT);
        }

        // check mask
        if (strpos('01', $mask) !== false) {
            // valid   11111111111111111111111100000000 -> 255.255.255.0
            // invalid 11111111111111111111111100000001 -> 255.255.255.1
            throw InvalidMaskException::ip($ip);
        }

        return new self($ip, substr_count($mask, '1'));
    }

    /**
     * @param int $cidr
     *
     * @return self
     */
    public static function fromCIDR($cidr)
    {
        if ($cidr < 0 || $cidr > 32) {
            throw InvalidMaskException::cidr($cidr);
        }

        // ip2long('255.255.255.255') == -1
        return new self(long2ip((-1 << (32 - $cidr)) & -1), $cidr);
    }

    /**
     * @return int
     */
    public function ip()
    {
        return $this->long;
    }

    /**
     * @return int
     */
    public function cidr()
    {
        return $this->cidr;
    }

    /**
     * @param IPv4NetworkMask $mask
     *
     * @return bool
     */
    public function equal(IPv4NetworkMask $mask)
    {
        return $this->long == $mask->ip();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->ip;
    }
}
