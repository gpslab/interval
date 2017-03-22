<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Interval\Date;

use GpsLab\Component\Interval\Date\DateInterval;
use PHPUnit\Framework\TestCase;

class DateIntervalTest extends TestCase
{
    public function testIterate()
    {
        $expected = [
            '2017-03-01',
            '2017-03-02',
            '2017-03-03',
            '2017-03-04',
            '2017-03-05',
        ];
        $interval = DateInterval::closed(new \DateTime('2017-03-01'), new \DateTime('2017-03-05'));

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
            '2017-03-03',
            '2017-03-05',
            '2017-03-07',
            '2017-03-09',
        ];
        $step = new \DateInterval('P2D');
        $interval = DateInterval::open(new \DateTime('2017-03-01'), new \DateTime('2017-03-11'));

        $points = [];
        foreach ($interval->iterate($step) as $point) {
            $points[] = $point->format('Y-m-d');
        }

        $this->assertEquals($expected, $points);
    }
}
