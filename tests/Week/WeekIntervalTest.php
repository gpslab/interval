<?php
/**
 * Pkvs package
 *
 * @package Pkvs
 * @author  Peter Gribanov <pgribanov@1tv.com>
 */

namespace GpsLab\Component\Tests\Interval\Week;

use GpsLab\Component\Interval\Week\WeekInterval;
use PHPUnit\Framework\TestCase;

class WeekIntervalTest extends TestCase
{
    public function testIterate()
    {
        $expected = [
            '2017-02-27',
            '2017-03-06',
            '2017-03-13',
            '2017-03-20',
            '2017-03-27',
        ];
        $interval = WeekInterval::closed(new \DateTime('2017-03-01'), new \DateTime('2017-03-30'));

        $points = [];
        foreach ($interval->iterate() as $point) {
            /* @var $point \DateTime */
            $points[] = $point->format('Y-m-d');
        }

        $this->assertEquals($expected, $points);
    }

    public function testIterateWithStep()
    {
        $expected = [
            '2017-03-13',
            '2017-03-27',
        ];
        $step = new \DateInterval('P2W');
        $interval = WeekInterval::open(new \DateTime('2017-03-01'), new \DateTime('2017-04-13'));

        $points = [];
        foreach ($interval->iterate($step) as $point) {
            $points[] = $point->format('Y-m-d');
        }

        $this->assertEquals($expected, $points);
    }
}
