<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Interval\Number;

use Generator;
use GpsLab\Component\Interval\Number\NumberInterval;
use PHPUnit\Framework\TestCase;

class NumberIntervalTest extends TestCase
{
    /**
     * @return array
     */
    public function getContainsPoints()
    {
        return [
            ['[2,5]', 1, false],
            ['[2,5]', 2, true],
            ['(2,5]', 2, false],
            ['[2,5]', 6, false],
            ['[2,5]', 5, true],
            ['[2,5)', 5, false],
            ['[-5,-2]', -3, true],
        ];
    }

    /**
     * @dataProvider getContainsPoints
     *
     * @param string $interval
     * @param int $point
     * @param bool $expected
     */
    public function testContains($interval, $point, $expected)
    {
        $interval = NumberInterval::fromString($interval);

        $this->assertEquals($expected, $interval->contains($point));
    }

    /**
     * @return array
     */
    public function getIntersectIntervals()
    {
        return [
            // not intersect
            ['[5,10]', '[1,4]', false],
            ['[5,10]', '[11,15]', false],
            // check interval type
            ['[5,10]', '[1,5]', true],
            ['(5,10]', '[1,5]', false],
            ['[5,10]', '[1,5)', false],
            ['[5,10]', '[10,15]', true],
            ['[5,10)', '[10,15]', false],
            ['[5,10]', '(10,15]', false],
        ];
    }

    /**
     * @dataProvider getIntersectIntervals
     *
     * @param string $origin_interval
     * @param string $compare_interval
     * @param bool $expected
     */
    public function testIntersect($origin_interval, $compare_interval, $expected)
    {
        $origin_interval = NumberInterval::fromString($origin_interval);
        $compare_interval = NumberInterval::fromString($compare_interval);

        $this->assertEquals($expected, $origin_interval->intersects($compare_interval));
    }

    /**
     * @return array
     */
    public function getIntersectIntervalIntervals()
    {
        return [
            ['[5,10]', '[1,7]', '[5,7]'],
            ['(5,10]', '[1,7)', '(5,7)'],
            ['[5,10]', '[6,9]', '[6,9]'], // inscribed
            ['[5,10]', '(6,9)', '(6,9)'], // inscribed
            ['[5,10]', '[7,15]', '[7,10]'],
            ['[5,10)', '(7,15]', '(7,10)'],
            ['[5,10]', '[1,15]', '[5,10]'], // describes
            ['(5,10)', '[1,15]', '(5,10)'], // describes
            // not intersect
            ['[5,10]', '[1,5]', null], // start = end
            ['[5,10]', '[10,15]', null], // end = start
            ['[5,10]', '[1,4]', null],
            ['[5,10]', '[11,15]', null],
        ];
    }

    /**
     * @dataProvider getIntersectIntervalIntervals
     *
     * @param string $origin_interval
     * @param string $compare_interval
     * @param string|null $expected_interval
     */
    public function testIntersectInterval($origin_interval, $compare_interval, $expected_interval)
    {
        $origin_interval = NumberInterval::fromString($origin_interval);
        $compare_interval = NumberInterval::fromString($compare_interval);
        $expected_interval = $expected_interval ? NumberInterval::fromString($expected_interval) : null;

        $this->assertEquals($expected_interval, $origin_interval->intersection($compare_interval));
    }

    /**
     * @return Generator<array-key, array{string, string, string|null}>
     */
    public function providerUnionInterval(): Generator
    {
        yield 'Closed with closed' => ['[5,10]', '[1,7]', '[1,10]'];
        yield 'Closed with closed (same)' => ['[5,10]', '[5,10]', '[5,10]'];
        yield 'Open with open' => ['(5,10)', '(1,7)', '(1,10)'];
        yield 'Open with open (same)' => ['(5,8)', '(5,8)', '(5,8)'];
        yield 'Open with closed' => ['(5,8)', '[5,8]', '[5,8]'];
        yield 'Closed with open' => ['[5,8]', '(5,8)', '[5,8]'];
        yield 'Not intersecting' => ['(1,2]', '[4,7)', null];
        yield 'Open start with open end' => ['(1,4]', '[4,7)', '(1,7)'];
        yield 'Open start with closed end' => ['(5,8]', '[6,9]', '(5,9]'];
        yield 'Closed start with open end' => ['[5,8]', '(6,9)', '[5,9)'];
    }

    /**
     * @dataProvider providerUnionInterval
     */
    public function testUnionInterval(string $a_interval, string $b_interval, ?string $expected_interval): void
    {
        $a = NumberInterval::fromString($a_interval);
        $b = NumberInterval::fromString($b_interval);
        $expected_interval = $expected_interval ? NumberInterval::fromString($expected_interval) : null;

        $this->assertEquals($expected_interval, $a->union($b));
    }

    public function testIterate()
    {
        $interval = NumberInterval::closed(1, 5);

        $points = [];
        foreach ($interval->iterate() as $point) {
            $points[] = $point;
        }

        $this->assertEquals([1, 2, 3, 4, 5], $points);
    }

    public function testIterateWithStep()
    {
        $step = 2;
        $interval = NumberInterval::open(0, 10);

        $points = [];
        foreach ($interval->iterate($step) as $point) {
            $points[] = $point;
        }

        $this->assertEquals([2, 4, 6, 8], $points);
    }
}
