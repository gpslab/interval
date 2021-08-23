<?php
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\DateTime;

use GpsLab\Component\Interval\Exception\IncorrectIntervalException;
use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;
use GpsLab\Component\Interval\IntervalComparator;
use GpsLab\Component\Interval\ComparableIntervalInterface;
use GpsLab\Component\Interval\IntervalPointInterface;
use GpsLab\Component\Interval\IntervalType;

class DateTimeInterval implements ComparableIntervalInterface
{
    /**
     * @var string
     */
    const REGEXP = '/^
        (?:\(|\[)                                       # start type char
        \s*
        (?<start>\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}) # start point
        \s*,\s*                                         # separator
        (?<end>\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2})   # end point
        \s*
        (?:\)|\])                                       # end type char
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
     * @var DateTimeIntervalPoint
     */
    private $start;

    /**
     * @var DateTimeIntervalPoint
     */
    private $end;

    private function __construct(DateTimeIntervalPoint $start, DateTimeIntervalPoint $end, IntervalType $type)
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
        return new self(new DateTimeIntervalPoint($start), new DateTimeIntervalPoint($end), $type);
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
     *   [2016-12-09 02:55:00, 2016-12-21 12:30:12]
     *   (2015-03-07 12:04:45, 2015-10-19 19:38:14]
     *   [2014-09-11 17:31:09, 2015-02-08 23:45:58)
     *   (2013-10-27 15:03:37, 2013-10-30 05:06:34)
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
            throw InvalidIntervalFormatException::create('[YYYY-MM-DD HH:II:SS, YYYY-MM-DD HH:II:SS]', $string);
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
     * @param DateTimeInterval $interval
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
        return $this->comparator->contains(new DateTimeIntervalPoint($point));
    }

    /**
     * Does this interval intersect the specified interval.
     *
     * @param DateTimeInterval $interval
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
     * @param DateTimeInterval $interval
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
     * @param DateTimeInterval $interval
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
     * @param DateTimeInterval $interval
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
     * @param DateTimeInterval $interval
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
     * @param DateTimeInterval $interval
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
     * @param DateTimeInterval $interval
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
        return $this->comparator->before(new DateTimeIntervalPoint($point));
    }

    /**
     * The point is after the interval.
     *
     * @return bool
     */
    public function after(\DateTime $point)
    {
        return $this->comparator->after(new DateTimeIntervalPoint($point));
    }

    /**
     * @return \Generator
     */
    public function iterate(\DateInterval $step = null)
    {
        $step = $step ?: new \DateInterval('P1D');

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
        $step = $step ?: new \DateInterval('P1D');

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
     * @return DateTimeIntervalPoint
     */
    public function startPoint()
    {
        return $this->start;
    }

    /**
     * @return DateTimeIntervalPoint
     */
    public function endPoint()
    {
        return $this->end;
    }

    /**
     * Returns a copy of this Interval with the start point altered.
     *
     * @param IntervalPointInterface|DateTimeIntervalPoint $start
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
     * @param IntervalPointInterface|DateTimeIntervalPoint $end
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
