<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Week;

use GpsLab\Component\Interval\Exception\IncorrectIntervalException;
use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;
use GpsLab\Component\Interval\IntervalComparator;
use GpsLab\Component\Interval\ComparableIntervalInterface;
use GpsLab\Component\Interval\IntervalPointInterface;
use GpsLab\Component\Interval\IntervalType;

class WeekInterval implements ComparableIntervalInterface
{
    /**
     * @var string
     */
    const REGEXP = '/^
        (?:\(|\[)                   # start type char
        \s*
        (?<start>\d{4}-\d{2}-\d{2}) # start point
        \s*,\s*                     # separator
        (?<end>\d{4}-\d{2}-\d{2})   # end point
        \s*
        (?:\)|\])                   # end type char
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
     * @var WeekIntervalPoint
     */
    private $start;

    /**
     * @var WeekIntervalPoint
     */
    private $end;

    /**
     * @param WeekIntervalPoint $start
     * @param WeekIntervalPoint $end
     * @param IntervalType $type
     */
    private function __construct(WeekIntervalPoint $start, WeekIntervalPoint $end, IntervalType $type)
    {
        if ($start->gte($end)) {
            throw IncorrectIntervalException::create();
        }

        $this->type = $type;
        $this->start = $start;
        $this->end = $end;
        $this->comparator = new IntervalComparator($this);
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @param IntervalType $type
     *
     * @return self
     */
    public static function create(\DateTime $start, \DateTime $end, IntervalType $type)
    {
        return new self(new WeekIntervalPoint($start), new WeekIntervalPoint($end), $type);
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return self
     */
    public static function closed(\DateTime $start, \DateTime $end)
    {
        return static::create($start, $end, IntervalType::closed());
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return self
     */
    public static function halfClosed(\DateTime $start, \DateTime $end)
    {
        return static::create($start, $end, IntervalType::halfClosed());
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return self
     */
    public static function halfOpen(\DateTime $start, \DateTime $end)
    {
        return static::create($start, $end, IntervalType::halfOpen());
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return self
     */
    public static function open(\DateTime $start, \DateTime $end)
    {
        return static::create($start, $end, IntervalType::open());
    }

    /**
     * Create interval from string.
     *
     * Example formats for all interval types:
     *   [2016-12-09, 2016-12-21]
     *   (2015-03-07, 2015-10-19]
     *   [2014-09-11, 2015-02-08)
     *   (2013-10-02, 2013-10-30)
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
            throw InvalidIntervalFormatException::create('[YYYY-MM-DD, YYYY-MM-DD]', $string);
        }

        return self::create(
            new \DateTime($match['start']),
            new \DateTime($match['end']),
            IntervalType::fromString($string)
        );
    }

    /**
     * Checks if this interval is equal to the specified interval.
     *
     * @param WeekInterval $interval
     *
     * @return bool
     */
    public function equal(WeekInterval $interval)
    {
        return $this->comparator->equal($interval);
    }

    /**
     * Does this interval contain the specified point.
     *
     * @param \DateTime $point
     *
     * @return bool
     */
    public function contains(\DateTime $point)
    {
        return $this->comparator->contains(new WeekIntervalPoint($point));
    }

    /**
     * Does this interval intersect the specified interval.
     *
     * @param WeekInterval $interval
     *
     * @return bool
     */
    public function intersects(WeekInterval $interval)
    {
        return $this->comparator->intersects($interval);
    }

    /**
     * Gets the intersection between this interval and another interval.
     *
     * @param WeekInterval $interval
     *
     * @return self|null
     */
    public function intersection(WeekInterval $interval)
    {
        return $this->comparator->intersection($interval);
    }

    /**
     * Gets the covered interval between this Interval and another interval.
     *
     * @param WeekInterval $interval
     *
     * @return self
     */
    public function cover(WeekInterval $interval)
    {
        return $this->comparator->cover($interval);
    }

    /**
     * Gets the gap between this interval and another interval.
     *
     * @param WeekInterval $interval
     *
     * @return self|null
     */
    public function gap(WeekInterval $interval)
    {
        return $this->comparator->gap($interval);
    }

    /**
     * Does this interval abut with the interval specified.
     *
     * @param WeekInterval $interval
     *
     * @return bool
     */
    public function abuts(WeekInterval $interval)
    {
        return $this->comparator->abuts($interval);
    }

    /**
     * Joins the interval between the adjacent.
     *
     * @param WeekInterval $interval
     *
     * @return self|null
     */
    public function join(WeekInterval $interval)
    {
        return $this->comparator->join($interval);
    }

    /**
     * Gets the union between this interval and another interval.
     *
     * @param WeekInterval $interval
     *
     * @return self|null
     */
    public function union(WeekInterval $interval)
    {
        return $this->comparator->union($interval);
    }

    /**
     * The point is before the interval.
     *
     * @param \DateTime $point
     *
     * @return bool
     */
    public function before(\DateTime $point)
    {
        return $this->comparator->before(new WeekIntervalPoint($point));
    }

    /**
     * The point is after the interval.
     *
     * @param \DateTime $point
     *
     * @return bool
     */
    public function after(\DateTime $point)
    {
        return $this->comparator->after(new WeekIntervalPoint($point));
    }

    /**
     * @param \DateInterval|null $step
     *
     * @return \Generator
     */
    public function iterate(\DateInterval $step = null)
    {
        $step = $step ?: new \DateInterval('P1W');

        $date = $this->start();
        $end = $this->end();

        if ($this->type->startExcluded()) {
            $date->add($step);
        }

        if ($this->type->endExcluded()) {
            $end->sub($step);
        }

        while ($date <= $end) {
            yield $date;
            $date->add($step);
        }
    }

    /**
     * @param \DateInterval|null $step
     *
     * @return \DatePeriod
     */
    public function period(\DateInterval $step = null)
    {
        $step = $step ?: new \DateInterval('P1W');

        return new \DatePeriod($this->start(), $step, $this->end());
    }

    /**
     * @return IntervalType
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return \DateTime
     */
    public function start()
    {
        return $this->start->value();
    }

    /**
     * @return \DateTime
     */
    public function end()
    {
        return $this->end->value();
    }

    /**
     * @return WeekIntervalPoint
     */
    public function startPoint()
    {
        return $this->start;
    }

    /**
     * @return WeekIntervalPoint
     */
    public function endPoint()
    {
        return $this->end;
    }

    /**
     * Returns a copy of this Interval with the start point altered.
     *
     * @param IntervalPointInterface|WeekIntervalPoint $start
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
     * @param IntervalPointInterface|WeekIntervalPoint $end
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
     * @param IntervalType $type
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
