<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Interval\IPv4;

use GpsLab\Component\Interval\IPv4\IPv4Interval;
use PHPUnit\Framework\TestCase;

class IPv4IntervalTest extends TestCase
{
    public function testIterate()
    {
        $expected = [
            '10.0.1.0',
            '10.0.1.1',
            '10.0.1.2',
            '10.0.1.3',
            '10.0.1.4',
            '10.0.1.5',
        ];
        $interval = IPv4Interval::closed('10.0.1.0', '10.0.1.5');

        $points = [];
        foreach ($interval->iterate() as $point) {
            $points[] = $point;
        }

        $this->assertEquals($expected, $points);
    }

    public function testIterateWithStep()
    {
        $expected = [
            '10.0.1.2',
            '10.0.1.4',
            '10.0.1.6',
            '10.0.1.8',
        ];
        $step = 2;
        $interval = IPv4Interval::open('10.0.1.0', '10.0.1.10');

        $points = [];
        foreach ($interval->iterate($step) as $point) {
            $points[] = $point;
        }

        $this->assertEquals($expected, $points);
    }
}
