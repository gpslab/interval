<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv6;

use GpsLab\Component\Interval\Exception\IncorrectIntervalException;
use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;
use GpsLab\Component\Interval\IntervalComparator;
use GpsLab\Component\Interval\ComparableIntervalInterface;
use GpsLab\Component\Interval\IntervalPointInterface;
use GpsLab\Component\Interval\IntervalType;

class IPv6Interval implements ComparableIntervalInterface
{
    /**
     * @var string
     */
    const REGEXP = '/^
        (?:\(|\[)                                  # start type char
        \s*
        (?<start>'.IPv6IntervalPoint::IPV6_ADDR.') # start point
        \s*,\s*                                    # separator
        (?<end>'.IPv6IntervalPoint::IPV6_ADDR.')   # end point
        \s*
        (?:\)|\])                                  # end type char
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
     * @var IPv6IntervalPoint
     */
    private $start;

    /**
     * @var IPv6IntervalPoint
     */
    private $end;

    /**
     * @param IPv6IntervalPoint $start
     * @param IPv6IntervalPoint $end
     * @param IntervalType $type
     */
    private function __construct(IPv6IntervalPoint $start, IPv6IntervalPoint $end, IntervalType $type)
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
     * @param string $start
     * @param string $end
     * @param IntervalType $type
     *
     * @return self
     */
    public static function create($start, $end, IntervalType $type)
    {
        return new self(new IPv6IntervalPoint($start), new IPv6IntervalPoint($end), $type);
    }

    /**
     * @param string $start
     * @param string $end
     *
     * @return self
     */
    public static function closed($start, $end)
    {
        return static::create($start, $end, IntervalType::closed());
    }

    /**
     * @param string $start
     * @param string $end
     *
     * @return self
     */
    public static function halfClosed($start, $end)
    {
        return static::create($start, $end, IntervalType::halfClosed());
    }

    /**
     * @param string $start
     * @param string $end
     *
     * @return self
     */
    public static function halfOpen($start, $end)
    {
        return static::create($start, $end, IntervalType::halfOpen());
    }

    /**
     * @param string $start
     * @param string $end
     *
     * @return self
     */
    public static function open($start, $end)
    {
        return static::create($start, $end, IntervalType::open());
    }

    /**
     * Create interval from string.
     *
     * Example formats for all interval types:
     *   [fe80::, febf::]
     *   (fe80::, febf::]
     *   [fec0::, feff::)
     *   (fec0::, feff::)
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
            $ipv6 = implode(':', array_fill(0, 8, 'ffff'));

            throw InvalidIntervalFormatException::create(sprintf('[%s, %s]', $ipv6, $ipv6), $string);
        }

        return self::create($match['start'], $match['end'], IntervalType::fromString($string));
    }

    /**
     * Checks if this interval is equal to the specified interval.
     *
     * @param IPv6Interval $interval
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
     * @param string $point
     *
     * @return bool
     */
    public function contains($point)
    {
        return $this->comparator->contains(new IPv6IntervalPoint($point));
    }

    /**
     * Does this interval intersect the specified interval.
     *
     * @param IPv6Interval $interval
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
     * @param IPv6Interval $interval
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
     * @param IPv6Interval $interval
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
     * @param IPv6Interval $interval
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
     * @param IPv6Interval $interval
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
     * @param IPv6Interval $interval
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
     * @param IPv6Interval $interval
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
     * @param string $point
     *
     * @return bool
     */
    public function before($point)
    {
        return $this->comparator->before(new IPv6IntervalPoint($point));
    }

    /**
     * The point is after the interval.
     *
     * @param string $point
     *
     * @return bool
     */
    public function after($point)
    {
        return $this->comparator->after(new IPv6IntervalPoint($point));
    }

    /**
     * @return IntervalType
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function start()
    {
        return (string) $this->start;
    }

    /**
     * @return string
     */
    public function end()
    {
        return (string) $this->end;
    }

    /**
     * @return IPv6IntervalPoint
     */
    public function startPoint()
    {
        return $this->start;
    }

    /**
     * @return IPv6IntervalPoint
     */
    public function endPoint()
    {
        return $this->end;
    }

    /**
     * Returns a copy of this Interval with the start point altered.
     *
     * @param IntervalPointInterface|IPv6IntervalPoint $start
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
     * @param IntervalPointInterface|IPv6IntervalPoint $end
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
