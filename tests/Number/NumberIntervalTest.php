<?php
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace Pkvs\Carousel\Tests\Interval\Number;

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
            ['[5,10]', '[1,7]', false, true],
            ['[5,10]', '[6,9]', false, true], // inscribed
            ['[5,10]', '[7,15]', false, true],
            ['[5,10]', '[1,15]', false, true], // describes
            ['[5,10]', '[1,5]', false, true], // start = end
            ['[5,10]', '[10,15]', false, true], // end = start
            // not intersect
            ['[5,10]', '[1,4]', false, false],
            ['[5,10]', '[11,15]', false, false],
            // check interval type
            ['[5,10]', '[1,5]', true, true],
            ['(5,10]', '[1,5]', true, false],
            ['[5,10]', '[1,5)', true, false],
            ['[5,10]', '[10,15]', true, true],
            ['[5,10)', '[10,15]', true, false],
            ['[5,10]', '(10,15]', true, false],
        ];
    }

    /**
     * @dataProvider getIntersectIntervals
     *
     * @param string $origin_interval
     * @param string $compare_interval
     * @param bool $check_interval_type
     * @param bool $expected
     */
    public function testIntersect($origin_interval, $compare_interval, $check_interval_type, $expected)
    {
        $origin_interval = NumberInterval::fromString($origin_interval);
        $compare_interval = NumberInterval::fromString($compare_interval);

        $this->assertEquals($expected, $origin_interval->intersects($compare_interval, $check_interval_type));
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
}
