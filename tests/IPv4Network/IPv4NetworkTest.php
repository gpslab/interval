<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Interval\IPv4Network;

use GpsLab\Component\Interval\IPv4Network\IPv4Network;
use GpsLab\Component\Interval\IPv4Network\IPv4NetworkMask;
use GpsLab\Component\Interval\IPv4Network\IPv4NetworkPoint;

class IPv4NetworkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function getNetworks()
    {
        return [
            ['10.0.0.0', '10.255.255.255', '255.0.0.0', 8],
            ['172.16.0.0', '172.31.255.255', '255.240.0.0', 12],
            ['192.168.0.0', '192.168.255.255', '255.255.0.0', 16],
        ];
    }

    /**
     * @dataProvider getNetworks
     *
     * @param string $start
     * @param string $end
     * @param string $mask
     * @param int $cidr
     */
    public function testFromCIDR($start, $end, $mask, $cidr)
    {
        $network = IPv4Network::fromCIDR($start, $cidr);

        $this->assertInstanceOf(IPv4Network::class, $network);

        $this->assertEquals($start, $network->start());
        $this->assertInstanceOf(IPv4NetworkPoint::class, $network->startPoint());
        $this->assertEquals($start, (string) $network->startPoint());

        $this->assertEquals($end, $network->end());
        $this->assertInstanceOf(IPv4NetworkPoint::class, $network->endPoint());
        $this->assertEquals($end, (string) $network->endPoint());

        $this->assertInstanceOf(IPv4NetworkMask::class, $network->mask());
        $this->assertEquals($mask, (string) $network->mask());
        $this->assertEquals($cidr, $network->cidr());
    }

    /**
     * @dataProvider getNetworks
     *
     * @param string $start
     * @param string $end
     * @param string $mask
     * @param int $cidr
     */
    public function testFromString($start, $end, $mask, $cidr)
    {
        $network = IPv4Network::fromString($start.'/'.$cidr);

        $this->assertInstanceOf(IPv4Network::class, $network);

        $this->assertEquals($start, $network->start());
        $this->assertEquals($end, $network->end());
        $this->assertEquals($mask, (string) $network->mask());
        $this->assertEquals($cidr, $network->cidr());
    }

    /**
     * @dataProvider getNetworks
     *
     * @param string $start
     * @param string $end
     * @param string $mask
     * @param int $cidr
     */
    public function testFromMask($start, $end, $mask, $cidr)
    {
        $network = IPv4Network::fromMask($start, $mask);

        $this->assertInstanceOf(IPv4Network::class, $network);

        $this->assertEquals($start, $network->start());
        $this->assertEquals($end, $network->end());
        $this->assertEquals($mask, (string) $network->mask());
        $this->assertEquals($cidr, $network->cidr());
    }

    /**
     * @return array
     */
    public function getNetworksCompactFormat()
    {
        return [
            // 10.0.0.0
            ['10.0.0/8', '10.0.0.0', '10.255.255.255', '255.0.0.0', 8],
            ['10.0/8', '10.0.0.0', '10.255.255.255', '255.0.0.0', 8],
            ['10/8', '10.0.0.0', '10.255.255.255', '255.0.0.0', 8],
            // 172.16.0.0
            ['172.16.0/12', '172.16.0.0', '172.31.255.255', '255.240.0.0', 12],
            ['172.16/12', '172.16.0.0', '172.31.255.255', '255.240.0.0', 12],
            // 192.168.0.0
            ['192.168.0/16', '192.168.0.0', '192.168.255.255', '255.255.0.0', 16],
            ['192.168/16', '192.168.0.0', '192.168.255.255', '255.255.0.0', 16],
        ];
    }

    /**
     * @dataProvider getNetworksCompactFormat
     *
     * @param string $string
     * @param string $start
     * @param string $end
     * @param string $mask
     * @param int $cidr
     */
    public function testFromStringCompactFormat($string, $start, $end, $mask, $cidr)
    {
        $network = IPv4Network::fromString($string);

        $this->assertInstanceOf(IPv4Network::class, $network);

        $this->assertEquals($start, $network->start());
        $this->assertEquals($end, $network->end());
        $this->assertEquals($mask, (string) $network->mask());
        $this->assertEquals($cidr, $network->cidr());
    }

    /**
     * @return array
     */
    public function getContainIPs()
    {
        return [
            ['10.0.0.0/8', '10.0.0.1', true],
            ['10.0.0.0/8', '11.0.0.0', false],
            ['172.16.0.0/12', '172.20.0.0', true],
            ['172.16.0.0/12', '172.32.0.0', false],
            ['192.168.0.0/16', '192.168.0.1', true],
            ['192.168.0.0/16', '192.169.0.0', false],
        ];
    }

    /**
     * @dataProvider getContainIPs
     *
     * @param string $network
     * @param string $ip
     * @param bool $expected
     */
    public function testContains($network, $ip, $expected)
    {
        $this->assertEquals($expected, IPv4Network::fromString($network)->contains($ip));
    }

    /**
     * @return array
     */
    public function getIntersectsNetworks()
    {
        return [
            ['10.0.0.0/8', '10.0.0.0/8', true],
            ['10.0.0.0/8', '172.16.0.0/12', false],
            ['10.0.0.0/8', '10.13.0.0/12', true],
        ];
    }

    /**
     * @dataProvider getIntersectsNetworks
     *
     * @param string $network_a
     * @param string $network_b
     * @param bool $expected
     */
    public function testIntersects($network_a, $network_b, $expected)
    {
        $network_a = IPv4Network::fromString($network_a);
        $network_b = IPv4Network::fromString($network_b);

        $this->assertEquals($expected, $network_a->intersects($network_b));
        $this->assertEquals($expected, $network_b->intersects($network_a));
    }
}
