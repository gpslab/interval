<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv4Network;

use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;
use GpsLab\Component\Interval\IntervalInterface;

class IPv4Network implements IntervalInterface
{
    /**
     * @var string
     */
    const REGEXP = '/^
        \s*
        (?<ip>\d{1,3}(?:\.\d{1,3}(?:\.\d{1,3}(?:\.\d{1,3})?)?)?) # IP supported compact format
        \/                                                       # separator
        (?<cidr>\d{1,2})                                         # CIDR bit
        \s*
    $/x';

    /**
     * @var IPv4NetworkPoint
     */
    private $start;

    /**
     * @var IPv4NetworkPoint
     */
    private $end;

    /**
     * @var IPv4NetworkMask
     */
    private $mask;

    /**
     * @var IPv4NetworkComparator
     */
    private $comparator;

    /**
     * @param IPv4NetworkPoint $ip
     * @param IPv4NetworkMask $mask
     */
    private function __construct(IPv4NetworkPoint $ip, IPv4NetworkMask $mask)
    {
        $this->start = $ip;
        $this->mask = $mask;
        $this->end = new IPv4NetworkPoint(long2ip(
            $ip->value() + 2 ** (32 - $mask->cidr()) - 1
        ));
        $this->comparator = new IPv4NetworkComparator($this);
    }

    /**
     * @param string $ip
     * @param int $cidr
     *
     * @return IPv4Network
     */
    public static function fromCIDR($ip, $cidr)
    {
        return new self(new IPv4NetworkPoint($ip), IPv4NetworkMask::fromCIDR($cidr));
    }

    /**
     * @param string $ip
     * @param string $mask
     *
     * @return IPv4Network
     */
    public static function fromMask($ip, $mask)
    {
        return new self(new IPv4NetworkPoint($ip), IPv4NetworkMask::fromIP($mask));
    }

    /**
     * Create network from string.
     *
     * Example formats of network:
     *   10.0.0.0/8
     *   172.16.0.0/12
     *   192.168.0.0/16
     *
     * Supported compact format:
     *   10/8
     *   172.16/12
     *   192.168/16
     *
     * Spaces are ignored in format.
     *
     * @param string $string
     *
     * @return self
     */
    public static function fromString($string)
    {
        if (!preg_match(self::REGEXP, $string, $match)) {
            throw InvalidIntervalFormatException::create('0.0.0.0/32', $string);
        }

        // fill IP compact format
        $match['ip'] .= str_repeat('.0', 3 - substr_count($match['ip'], '.'));

        return self::fromCIDR($match['ip'], $match['cidr']);
    }

    /**
     * Checks if this network is equal to the specified network.
     *
     * @param IPv4Network $network
     *
     * @return bool
     */
    public function equal(IPv4Network $network)
    {
        return $this->comparator->equal($network);
    }

    /**
     * Does this network contain the specified IP.
     *
     * @param string $point
     *
     * @return bool
     */
    public function contains($point)
    {
        return $this->comparator->contains(new IPv4NetworkPoint($point));
    }

    /**
     * Does this network intersect the specified network.
     *
     * @param IPv4Network $network
     *
     * @return bool
     */
    public function intersects(IPv4Network $network)
    {
        return $this->comparator->intersects($network);
    }

    /**
     * Does this network abut with the network specified.
     *
     * @param IPv4Network $network
     *
     * @return bool
     */
    public function abuts(IPv4Network $network)
    {
        return $this->comparator->abuts($network);
    }

    /**
     * @param int $step
     *
     * @return \Generator
     */
    public function iterate($step = 1)
    {
        $end = $this->endPoint()->value();
        $ip = $this->startPoint()->value();

        while ($ip < $end) {
            yield long2ip($ip);
            $ip += $step;
        }
    }

    /**
     * @return string
     */
    public function start()
    {
        return (string) $this->start;
    }

    /**
     * @return string
     */
    public function end()
    {
        return (string) $this->end;
    }

    /**
     * @return IPv4NetworkMask
     */
    public function mask()
    {
        return $this->mask;
    }

    /**
     * @return int
     */
    public function cidr()
    {
        return $this->mask->cidr();
    }

    /**
     * @return IPv4NetworkPoint
     */
    public function startPoint()
    {
        return $this->start;
    }

    /**
     * @return IPv4NetworkPoint
     */
    public function endPoint()
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->start . '/' . $this->mask->cidr();
    }
}
