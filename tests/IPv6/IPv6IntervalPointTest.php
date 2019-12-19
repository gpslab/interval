<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Interval\IPv6;

use GpsLab\Component\Interval\IPv6\IPv6IntervalPoint;
use PHPUnit\Framework\TestCase;

class IPv6IntervalPointTest extends TestCase
{
    /**
     * @return array
     */
    public function getInvalidIPs()
    {
        return [
            [''],
            ['127.0.0.1'],
            ['::fg'],
            ['2001:0db8:11a3:09d7:1f34:8a2e:07a0:765d:0630'],
        ];
    }

    /**
     * @dataProvider getInvalidIPs
     * @expectedException \GpsLab\Component\Interval\Exception\InvalidPointTypeException
     *
     * @param string $ip
     */
    public function testInvalidIP($ip)
    {
        new IPv6IntervalPoint($ip);
    }

    /**
     * @return array
     */
    public function getValidIPs()
    {
        return [
            ['::'],
            ['::1'],
            ['2001:630:d0::'],
            ['2001:630:d0::07a0:65d'],
            ['2001:0db8:11a3::1f34:8a2e:07a0:765d'],
            ['2001:0db8:11a3:09d7:1f34:8a2e:07a0:765d'],
            ['::ffff:255.255.255.255'],
            ['2001:db8:3:4::192.0.2.33'],
        ];
    }

    /**
     * @dataProvider getValidIPs
     *
     * @param string $ip
     */
    public function testValidIP($ip)
    {
        $point1 = new IPv6IntervalPoint($ip);
        $point2 = new IPv6IntervalPoint($ip);

        $this->assertEquals($ip, (string) $point1);
        $this->assertEquals(inet_pton($ip), $point1->value());

        $this->assertTrue($point1->eq($point2));
    }

    /**
     * @return array
     */
    public function getLessThanIPs()
    {
        return [
            ['::1', '::2'],
            ['2001:630:d0::', '2001:630:d0::1'],
            ['2001:630:d0::07a0:65d', '2001:630:d0::07a2:65d'],
            ['2001:0db8:11a3::1f34:8a2e:07a0:765d', '2001:0db8:11a4::1f34:8a2e:07a0:765d'],
            ['2001:0db8:11a3:09d7:1f34:8a2e:07a0:765d', '2001:0db8:11a3:09d8:1f34:8a2e:07a0:765d'],
            ['::ffff:192.0.2.33', '::ffff:255.255.255.255'],
            ['2001:db8:3:4::192.0.0.33', '2001:db8:3:4::192.0.2.33'],
        ];
    }

    /**
     * @dataProvider getLessThanIPs
     *
     * @param string $ip1
     * @param string $ip2
     */
    public function testLessThan($ip1, $ip2)
    {
        $point1 = new IPv6IntervalPoint($ip1);
        $point2 = new IPv6IntervalPoint($ip2);

        $this->assertTrue($point1->lt($point2));
    }
}
