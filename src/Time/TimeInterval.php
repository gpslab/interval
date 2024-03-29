<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Time;

use GpsLab\Component\Interval\Exception\IncorrectIntervalException;
use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;
use GpsLab\Component\Interval\IntervalComparator;
use GpsLab\Component\Interval\ComparableIntervalInterface;
use GpsLab\Component\Interval\IntervalPointInterface;
use GpsLab\Component\Interval\IntervalType;

class TimeInterval implements ComparableIntervalInterface
{
    /**
     * @var string
     */
    const REGEXP = '/^
        (?:\(|\[)                   # start type char
        \s*
        (?<start>\d{2}:\d{2}:\d{2}) # start point
        \s*,\s*                     # separator
        (?<end>\d{2}:\d{2}:\d{2})   # end point
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
     * @var TimeIntervalPoint
     */
    private $start;

    /**
     * @var TimeIntervalPoint
     */
    private $end;

    private function __construct(TimeIntervalPoint $start, TimeIntervalPoint $end, IntervalType $type)
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
     * @return self
     */
    public static function create(\DateTime $start, \DateTime $end, IntervalType $type)
    {
        return new self(new TimeIntervalPoint($start), new TimeIntervalPoint($end), $type);
    }

    /**
     * @return self
     */
    public static function closed(\DateTime $start, \DateTime $end)
    {
        return static::create($start, $end, IntervalType::closed());
    }

    /**
     * @return self
     */
    public static function halfClosed(\DateTime $start, \DateTime $end)
    {
        return static::create($start, $end, IntervalType::halfClosed());
    }

    /**
     * @return self
     */
    public static function halfOpen(\DateTime $start, \DateTime $end)
    {
        return static::create($start, $end, IntervalType::halfOpen());
    }

    /**
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
     *   [02:55:00, 12:30:12]
     *   (12:04:45, 19:38:14]
     *   [17:31:09, 23:45:58)
     *   (15:03:37, 15:06:34)
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
            throw InvalidIntervalFormatException::create('[HH:II:SS, HH:II:SS]', $string);
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
     * @param TimeInterval $interval
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
     * @return bool
     */
    public function contains(\DateTime $point)
    {
        return $this->comparator->contains(new TimeIntervalPoint($point));
    }

    /**
     * Does this interval intersect the specified interval.
     *
     * @param TimeInterval $interval
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
     * @param TimeInterval $interval
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
     * @param TimeInterval $interval
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
     * @param TimeInterval $interval
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
     * @param TimeInterval $interval
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
     * @param TimeInterval $interval
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
     * @param TimeInterval $interval
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
     * @return bool
     */
    public function before(\DateTime $point)
    {
        return $this->comparator->before(new TimeIntervalPoint($point));
    }

    /**
     * The point is after the interval.
     *
     * @return bool
     */
    public function after(\DateTime $point)
    {
        return $this->comparator->after(new TimeIntervalPoint($point));
    }

    /**
     * @return \Generator
     */
    public function iterate(\DateInterval $step = null)
    {
        $step = $step ?: new \DateInterval('PT1M');

        $date = $this->start();
        $end = $this->end();

        if ($this->type->startExcluded()) {
            $date->add($step);
        }

        while ($date < $end || (!$this->type->endExcluded() && $date == $end)) {
            yield $date;
            $date->add($step);
        }
    }

    /**
     * @return \DatePeriod
     */
    public function period(\DateInterval $step = null)
    {
        $step = $step ?: new \DateInterval('PT1M');

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
     * @return TimeIntervalPoint
     */
    public function startPoint()
    {
        return $this->start;
    }

    /**
     * @return TimeIntervalPoint
     */
    public function endPoint()
    {
        return $this->end;
    }

    /**
     * Returns a copy of this Interval with the start point altered.
     *
     * @param IntervalPointInterface|TimeIntervalPoint $start
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
     * @param IntervalPointInterface|TimeIntervalPoint $end
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
