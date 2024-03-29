<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Number;

use GpsLab\Component\Interval\Exception\IncorrectIntervalException;
use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;
use GpsLab\Component\Interval\IntervalComparator;
use GpsLab\Component\Interval\ComparableIntervalInterface;
use GpsLab\Component\Interval\IntervalPointInterface;
use GpsLab\Component\Interval\IntervalType;

class NumberInterval implements ComparableIntervalInterface
{
    /**
     * @var string
     */
    const REGEXP = '/^
        (?:\(|\[)        # start type char
        \s*
        (?<start>\-?\d+) # start point
        \s*,\s*          # separator
        (?<end>\-?\d+)   # end point
        \s*
        (?:\)|\])        # end type char
    $/x';

    /**
     * @var IntervalType
     */
    private $type;

    /**
     * @var IntervalComparator
     */
    private $comparator;

    /**
     * @var NumberIntervalPoint
     */
    private $start;

    /**
     * @var NumberIntervalPoint
     */
    private $end;

    private function __construct(NumberIntervalPoint $start, NumberIntervalPoint $end, IntervalType $type)
    {
        if ($start->gte($end)) {
            throw IncorrectIntervalException::create();
        }

        $this->start = $start;
        $this->end = $end;
        $this->type = $type;
        $this->comparator = new IntervalComparator($this);
    }

    /**
     * @param int|float $start
     * @param int|float $end
     *
     * @return self
     */
    public static function create($start, $end, IntervalType $type)
    {
        return new self(new NumberIntervalPoint($start), new NumberIntervalPoint($end), $type);
    }

    /**
     * @param int|float $start
     * @param int|float $end
     *
     * @return self
     */
    public static function closed($start, $end)
    {
        return self::create($start, $end, IntervalType::closed());
    }

    /**
     * @param int|float $start
     * @param int|float $end
     *
     * @return self
     */
    public static function halfClosed($start, $end)
    {
        return self::create($start, $end, IntervalType::halfClosed());
    }

    /**
     * @param int|float $start
     * @param int|float $end
     *
     * @return self
     */
    public static function halfOpen($start, $end)
    {
        return self::create($start, $end, IntervalType::halfOpen());
    }

    /**
     * @param int|float $start
     * @param int|float $end
     *
     * @return self
     */
    public static function open($start, $end)
    {
        return self::create($start, $end, IntervalType::open());
    }

    /**
     * Create interval from string.
     *
     * Example formats:
     *   [0, 5]
     *   (-3, 2]
     *   [-3, -1)
     *   (3, 9)
     *
     * Spaces are ignored in format.
     *
     * @param string $string
     *
     * @return self
     */
    public static function fromString($string)
    {
        if (!preg_match(self::REGEXP, $string, $match)) {
            throw InvalidIntervalFormatException::create('[N, N]', $string);
        }

        return self::create($match['start'], $match['end'], IntervalType::fromString($string));
    }

    /**
     * Checks if this interval is equal to the specified interval.
     *
     * @param NumberInterval $interval
     *
     * @return bool
     */
    public function equal(self $interval)
    {
        return $this->comparator->equal($interval);
    }

    /**
     * Does this interval contain the specified point.
     *
     * @param int|float $point
     *
     * @return bool
     */
    public function contains($point)
    {
        return $this->comparator->contains(new NumberIntervalPoint($point));
    }

    /**
     * Does this interval intersect the specified interval.
     *
     * @param NumberInterval $interval
     *
     * @return bool
     */
    public function intersects(self $interval)
    {
        return $this->comparator->intersects($interval);
    }

    /**
     * Gets the intersection between this interval and another interval.
     *
     * @param NumberInterval $interval
     *
     * @return self|null
     */
    public function intersection(self $interval)
    {
        return $this->comparator->intersection($interval);
    }

    /**
     * Gets the covered interval between this Interval and another interval.
     *
     * @param NumberInterval $interval
     *
     * @return self
     */
    public function cover(self $interval)
    {
        return $this->comparator->cover($interval);
    }

    /**
     * Gets the gap between this interval and another interval.
     *
     * @param NumberInterval $interval
     *
     * @return self|null
     */
    public function gap(self $interval)
    {
        return $this->comparator->gap($interval);
    }

    /**
     * Does this interval abuts with the interval specified.
     *
     * @param NumberInterval $interval
     *
     * @return bool
     */
    public function abuts(self $interval)
    {
        return $this->comparator->abuts($interval);
    }

    /**
     * Joins the interval between the adjacent.
     *
     * @param NumberInterval $interval
     *
     * @return self|null
     */
    public function join(self $interval)
    {
        return $this->comparator->join($interval);
    }

    /**
     * Gets the union between this interval and another interval.
     *
     * @param NumberInterval $interval
     *
     * @return self|null
     */
    public function union(self $interval)
    {
        return $this->comparator->union($interval);
    }

    /**
     * The point is before the interval.
     *
     * @param int|float $point
     *
     * @return bool
     */
    public function before($point)
    {
        return $this->comparator->before(new NumberIntervalPoint($point));
    }

    /**
     * The point is after the interval.
     *
     * @param int|float $point
     *
     * @return bool
     */
    public function after($point)
    {
        return $this->comparator->after(new NumberIntervalPoint($point));
    }

    /**
     * @param int $step
     *
     * @return \Generator
     */
    public function iterate($step = 1)
    {
        $end = $this->end();
        $number = $this->start();

        if ($this->type->startExcluded()) {
            $number += $step;
        }

        while ($number < $end || (!$this->type->endExcluded() && $number == $end)) {
            yield $number;
            $number += $step;
        }
    }

    /**
     * @return IntervalType
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return int|float
     */
    public function start()
    {
        return $this->start->value();
    }

    /**
     * @return int|float
     */
    public function end()
    {
        return $this->end->value();
    }

    /**
     * @return NumberIntervalPoint
     */
    public function startPoint()
    {
        return $this->start;
    }

    /**
     * @return NumberIntervalPoint
     */
    public function endPoint()
    {
        return $this->end;
    }

    /**
     * Returns a copy of this Interval with the start point altered.
     *
     * @param IntervalPointInterface|NumberIntervalPoint $start
     *
     * @return self
     */
    public function withStart(IntervalPointInterface $start)
    {
        return new self($start, $this->end, $this->type);
    }

    /**
     * Returns a copy of this Interval with the end point altered.
     *
     * @param IntervalPointInterface|NumberIntervalPoint $end
     *
     * @return self
     */
    public function withEnd(IntervalPointInterface $end)
    {
        return new self($this->start, $end, $this->type);
    }

    /**
     * Returns a copy of this Interval with the interval type altered.
     *
     * @return self
     */
    public function withType(IntervalType $type)
    {
        return new self($this->start, $this->end, $type);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->type->formatInterval($this);
    }
}
