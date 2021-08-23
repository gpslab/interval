<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv6Network;

use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;

class IPv6Network
{
    /**
     * @var string
     */
    const CIDR = '(1[0-2][0-9]|[1-9]?[0-9])'; // 0-128

    /**
     * @var string
     */
    const REGEXP = '/^
        \s*
        (?<ip>'.IPv6NetworkPoint::IPV6_ADDR.') # IP
        \/                                     # separator
        (?<cidr>'.self::CIDR.')                # CIDR bit
        \s*
    $/x';

    /**
     * @var IPv6NetworkPoint
     */
    private $start;

    /**
     * @var IPv6NetworkPoint
     */
    private $end;

    /**
     * @var int
     */
    private $mask;

    /**
     * @var IPv6NetworkComparator
     */
    private $comparator;

    /**
     * @param int $mask
     */
    private function __construct(IPv6NetworkPoint $ip, $mask)
    {
        $this->start = $ip;
        $this->mask = $mask;
        $this->comparator = new IPv6NetworkComparator($this);

        // calculate end point
        $end = inet_ntop($ip->value() | ~inet_pton($this->getIPMaskFromCIDR($mask)));
        $end = preg_replace('/:(0:)+/', '::', $end, 1); // format end ip
        $this->end = new IPv6NetworkPoint($end);
    }

    /**
     * @param int $cidr
     *
     * @return string
     */
    private function getIPMaskFromCIDR($cidr)
    {
        // bin mask
        $bin = str_repeat('1', $cidr).str_repeat('0', 128 - $cidr);

        // convert bin to hex
        $hex = [];
        foreach (str_split($bin, 16) as $octet) {
            $hex[] = dechex(bindec($octet)) ?: '0000';
        }

        return implode(':', $hex);
    }

    /**
     * @param string $ip
     * @param int $cidr
     *
     * @return IPv6Network
     */
    public static function fromCIDR($ip, $cidr)
    {
        if ($cidr < 0 || $cidr > 128) {
            throw InvalidMaskException::create($cidr);
        }

        return new self(new IPv6NetworkPoint($ip), $cidr);
    }

    /**
     * Create network from string.
     *
     * Example formats of network:
     *   2001:db8:d0::/33
     *   2001:db8::/64
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
            $ipv6 = implode(':', array_fill(0, 8, 'ffff'));

            throw InvalidIntervalFormatException::create($ipv6.'/128', $string);
        }

        return self::fromCIDR($match['ip'], $match['cidr']);
    }

    /**
     * Checks if this network is equal to the specified network.
     *
     * @param IPv6Network $network
     *
     * @return bool
     */
    public function equal(self $network)
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
        return $this->comparator->contains(new IPv6NetworkPoint($point));
    }

    /**
     * Does this network intersect the specified network.
     *
     * @param IPv6Network $network
     *
     * @return bool
     */
    public function intersects(self $network)
    {
        return $this->comparator->intersects($network);
    }

    /**
     * Does this network abut with the network specified.
     *
     * @param IPv6Network $network
     *
     * @return bool
     */
    public function abuts(self $network)
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
     * @return int
     */
    public function mask()
    {
        return $this->mask;
    }

    /**
     * @return IPv6NetworkPoint
     */
    public function startPoint()
    {
        return $this->start;
    }

    /**
     * @return IPv6NetworkPoint
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
        return $this->start.'/'.$this->mask;
    }
}
