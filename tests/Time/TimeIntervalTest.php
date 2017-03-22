<?php
/**
 * Pkvs package
 *
 * @package Pkvs
 * @author  Peter Gribanov <pgribanov@1tv.com>
 */

namespace GpsLab\Component\Tests\Interval\Time;

use GpsLab\Component\Interval\Time\TimeInterval;
use PHPUnit\Framework\TestCase;

class TimeIntervalTest extends TestCase
{
    public function testIterate()
    {
        $expected = [
            '16:30:15',
            '16:31:15',
            '16:32:15',
            '16:33:15',
            '16:34:15',
            '16:35:15',
        ];
        $interval = TimeInterval::closed(new \DateTime('16:30:15'), new \DateTime('16:35:42'));

        $points = [];
        foreach ($interval->iterate() as $point) {
            /* @var $point \DateTime */
            $points[] = $point->format('H:i:s');
        }

        $this->assertEquals($expected, $points);
    }

    public function testIterateWithStep()
    {
        $expected = [
            '16:32:15',
            '16:34:15',
            '16:36:15',
            '16:38:15',
            '16:40:15', // less than end point
        ];
        $step = new \DateInterval('PT2M');
        $interval = TimeInterval::open(new \DateTime('16:30:15'), new \DateTime('16:40:42'));

        $points = [];
        foreach ($interval->iterate($step) as $point) {
            $points[] = $point->format('H:i:s');
        }

        $this->assertEquals($expected, $points);
    }
}
