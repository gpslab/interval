<?php
/**
 * Pkvs package
 *
 * @package Pkvs
 * @author  Peter Gribanov <pgribanov@1tv.com>
 */

namespace GpsLab\Component\Tests\Interval\Year;

use GpsLab\Component\Interval\Year\YearInterval;
use PHPUnit\Framework\TestCase;

class YearIntervalTest extends TestCase
{
    public function testIterate()
    {
        $expected = [
            '2017-01-01',
            '2018-01-01',
            '2019-01-01',
            '2020-01-01',
            '2021-01-01',
            '2022-01-01',
        ];
        $interval = YearInterval::closed(new \DateTime('2017-03-07'), new \DateTime('2022-08-30'));

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
            '2019-01-01',
            '2021-01-01',
            '2023-01-01',
            '2025-01-01',
        ];
        $step = new \DateInterval('P2Y');
        $interval = YearInterval::open(new \DateTime('2017-01-07'), new \DateTime('2027-12-30'));

        $points = [];
        foreach ($interval->iterate($step) as $point) {
            $points[] = $point->format('Y-m-d');
        }

        $this->assertEquals($expected, $points);
    }
}
