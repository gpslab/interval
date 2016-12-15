<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Interval\IPv6Network;

use GpsLab\Component\Interval\IPv6Network\IPv6Network;
use GpsLab\Component\Interval\IPv6Network\IPv6NetworkPoint;

class IPv6NetworkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function getNetworks()
    {
        return [
            ['::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 0],
            ['::', '3fff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 2],
            ['2001::', '2001:fff:ffff:ffff:ffff:ffff:ffff:ffff', 20],
            ['2001:db8::', '2001:db8::7f:ffff:ffff:ffff:ffff', 57],
            ['2001:db8::', '2001:db8::7fff:ffff:ffff:ffff', 65],
            ['2001:db8:3:4::192.0.0.33', '2001:db8:3:4::ffff:ffff', 96],
        ];
    }

    /**
     * @dataProvider getNetworks
     *
     * @param string $start
     * @param string $end
     * @param int $mask
     */
    public function testFromCIDR($start, $end, $mask)
    {
        $network = IPv6Network::fromCIDR($start, $mask);

        $this->assertInstanceOf(IPv6Network::class, $network);

        $this->assertEquals($start, $network->start());
        $this->assertInstanceOf(IPv6NetworkPoint::class, $network->startPoint());
        $this->assertEquals($start, (string) $network->startPoint());

        $this->assertEquals($end, $network->end());
        $this->assertInstanceOf(IPv6NetworkPoint::class, $network->endPoint());
        $this->assertEquals($end, (string) $network->endPoint());

        $this->assertEquals($mask, $network->mask());
    }

    /**
     * @dataProvider getNetworks
     *
     * @param string $start
     * @param string $end
     * @param int $mask
     */
    public function testFromString($start, $end, $mask)
    {
        $network = IPv6Network::fromString($start.'/'.$mask);

        $this->assertInstanceOf(IPv6Network::class, $network);

        $this->assertEquals($start, $network->start());
        $this->assertEquals($end, $network->end());
        $this->assertEquals($mask, $network->mask());
    }

    /**
     * @return array
     */
    public function getContainIPs()
    {
        return [
            ['2001:db8::/57', '2001:db8::7:ffff:ffff:ffff:ffff', true],
            ['2001:db8::/57', '2001:db8:b1::', false],
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
        $this->assertEquals($expected, IPv6Network::fromString($network)->contains($ip));
    }

    /**
     * @return array
     */
    public function getIntersectsNetworks()
    {
        return [
            ['2001:db8::/57', '2001:db8::/57', true],
            ['2001:db8::/57', '2001:db8::/65', true],
            ['64:ff9b::/96', 'fec0::/10', false],
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
        $network_a = IPv6Network::fromString($network_a);
        $network_b = IPv6Network::fromString($network_b);

        $this->assertEquals($expected, $network_a->intersects($network_b));
        $this->assertEquals($expected, $network_b->intersects($network_a));
    }
}
