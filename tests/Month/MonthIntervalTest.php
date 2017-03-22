<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Interval\Month;

use GpsLab\Component\Interval\Month\MonthInterval;
use PHPUnit\Framework\TestCase;

class MonthIntervalTest extends TestCase
{
    public function testIterate()
    {
        $expected = [
            '2017-03-01',
            '2017-04-01',
            '2017-05-01',
            '2017-06-01',
            '2017-07-01',
            '2017-08-01',
        ];
        $interval = MonthInterval::closed(new \DateTime('2017-03-07'), new \DateTime('2017-08-30'));

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
            '2017-03-01',
            '2017-05-01',
            '2017-07-01',
            '2017-09-01',
            '2017-11-01',
        ];
        $step = new \DateInterval('P2M');
        $interval = MonthInterval::open(new \DateTime('2017-01-07'), new \DateTime('2017-12-30'));

        $points = [];
        foreach ($interval->iterate($step) as $point) {
            $points[] = $point->format('Y-m-d');
        }

        $this->assertEquals($expected, $points);
    }
}
