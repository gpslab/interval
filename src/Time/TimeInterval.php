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

    /**
     * @param TimeIntervalPoint $start
     * @param TimeIntervalPoint $end
     * @param IntervalType $type
     */
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
     * @param \DateTime $start
     * @param \DateTime $end
     * @param IntervalType $type
     *
     * @return self
     */
    public static function create(\DateTime $start, \DateTime $end, IntervalType $type)
    {
        return new self(new TimeIntervalPoint($start), new TimeIntervalPoint($end), $type);
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
     * @param TimeInterval $interval
     *
     * @return bool
     */
    public function equal(TimeInterval $interval)
    {
        return $this->comparator->equal($interval);
    }

    /**
     * @param \DateTime $point
     *
     * @return bool
     */
    public function contains(\DateTime $point)
    {
        return $this->comparator->contains(new TimeIntervalPoint($point));
    }

    /**
     * @param TimeInterval $interval
     *
     * @return bool
     */
    public function intersects(TimeInterval $interval)
    {
        return $this->comparator->intersects($interval);
    }

    /**
     * @param TimeInterval $interval
     *
     * @return self|null
     */
    public function intersection(TimeInterval $interval)
    {
        return $this->comparator->intersection($interval);
    }

    /**
     * @param TimeInterval $interval
     *
     * @return self
     */
    public function cover(TimeInterval $interval)
    {
        return $this->comparator->cover($interval);
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
        return $this->comparator->before(new TimeIntervalPoint($point));
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
        return $this->comparator->after(new TimeIntervalPoint($point));
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
        return $this->type->getReadable($this);
    }
}
