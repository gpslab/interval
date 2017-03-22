<?php
/**
 * Pkvs package
 *
 * @package Pkvs
 * @author  Peter Gribanov <pgribanov@1tv.com>
 */

namespace GpsLab\Component\Tests\Interval\DateTime;

use GpsLab\Component\Interval\DateTime\DateTimeInterval;
use PHPUnit\Framework\TestCase;

class DateTimeIntervalTest extends TestCase
{
    public function testIterate()
    {
        $expected = [
            '2017-03-01 04:30:15',
            '2017-03-02 04:30:15',
            '2017-03-03 04:30:15',
            '2017-03-04 04:30:15',
            '2017-03-05 04:30:15',
        ];
        $interval = DateTimeInterval::closed(
            new \DateTime('2017-03-01 04:30:15'),
            new \DateTime('2017-03-05 21:12:42')
        );

        $points = [];
        foreach ($interval->iterate() as $point) {
            /* @var $point \DateTime */
            $points[] = $point->format('Y-m-d H:i:s');
        }

        $this->assertEquals($expected, $points);
    }

    public function testIterateWithStep()
    {
        $expected = [
            '2017-03-03 04:30:15',
            '2017-03-05 04:30:15',
            '2017-03-07 04:30:15',
            '2017-03-09 04:30:15',
        ];
        $step = new \DateInterval('P2D');
        $interval = DateTimeInterval::open(
            new \DateTime('2017-03-01 04:30:15'),
            new \DateTime('2017-03-11 21:12:42')
        );

        $points = [];
        foreach ($interval->iterate($step) as $point) {
            $points[] = $point->format('Y-m-d H:i:s');
        }

        $this->assertEquals($expected, $points);
    }
}
