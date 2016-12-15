<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Interval\IPv6;

use GpsLab\Component\Interval\IntervalType;
use GpsLab\Component\Interval\IPv6\IPv6Interval;

class IPv6IntervalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function getNetworks()
    {
        return [
            ['2001:630:d0::', '2001:630:d0::ffff', IntervalType::TYPE_CLOSED],
            ['2001:0db8:11a3::1f34:8a2e:07a0:765d', '2001:0db8:11a4::1f34:8a2e:07a0:765d', IntervalType::TYPE_OPEN],
            ['::ffff:172.16.0.0', '::ffff:172.31.255.255', IntervalType::TYPE_HALF_CLOSED],
            ['2001:db8:3:4::192.168.0.0', '2001:db8:3:4::192.168.255.255', IntervalType::TYPE_HALF_OPEN],
        ];
    }

    /**
     * @dataProvider getNetworks
     *
     * @param string $start
     * @param string $end
     * @param int $type
     */
    public function testFromString($start, $end, $type)
    {
        $string = $start.', '.$end;

        if (($type & IntervalType::TYPE_START_EXCLUDED) == IntervalType::TYPE_START_EXCLUDED) {
            $string = '('.$string;
        } else {
            $string = '['.$string;
        }

        if (($type & IntervalType::TYPE_END_EXCLUDED) == IntervalType::TYPE_END_EXCLUDED) {
            $string .= ')';
        } else {
            $string .= ']';
        }

        $interval = IPv6Interval::fromString($string);

        $this->assertInstanceOf(IPv6Interval::class, $interval);

        $this->assertEquals($start, $interval->start());
        $this->assertEquals($end, $interval->end());
        $this->assertEquals($string, (string) $interval);
        $this->assertEquals($type, $interval->type()->type());

        $interval2 = IPv6Interval::create($start, $end, IntervalType::create($type));
        $this->assertEquals($interval, $interval2);

        switch ($type) {
            case IntervalType::TYPE_CLOSED:
                $interval3 = IPv6Interval::closed($start, $end);
                break;
            case IntervalType::TYPE_OPEN:
                $interval3 = IPv6Interval::open($start, $end);
                break;
            case IntervalType::TYPE_HALF_CLOSED:
                $interval3 = IPv6Interval::halfClosed($start, $end);
                break;
            case IntervalType::TYPE_HALF_OPEN:
                $interval3 = IPv6Interval::halfOpen($start, $end);
                break;
            default: // bad interval type
                $interval3 = null;
        }

        $this->assertEquals($interval, $interval3);
    }
}
