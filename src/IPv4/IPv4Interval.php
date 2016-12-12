<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv4;

use GpsLab\Component\Interval\Exception\IncorrectIntervalException;
use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;
use GpsLab\Component\Interval\IntervalComparator;
use GpsLab\Component\Interval\ComparableIntervalInterface;
use GpsLab\Component\Interval\IntervalType;

class IPv4Interval implements ComparableIntervalInterface
{
    /**
     * @var string
     */
    const REGEXP = '/^
        (?:\(|\[)                                    # start type char
        \s*
        (?<start>\d{1-3}\.\d{1-3}\.\d{1-3}\.\d{1-3}) # start point
        \s*,\s*                                      # separator
        (?<end>\d{1-3}\.\d{1-3}\.\d{1-3}\.\d{1-3})   # end point
        \s*
        (?:\)|\])                                    # end type char
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
     * @var IPv4IntervalPoint
     */
    private $start;

    /**
     * @var IPv4IntervalPoint
     */
    private $end;

    /**
     * @param IPv4IntervalPoint $start
     * @param IPv4IntervalPoint $end
     * @param IntervalType $type
     */
    private function __construct(IPv4IntervalPoint $start, IPv4IntervalPoint $end, IntervalType $type)
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
        return new self(new IPv4IntervalPoint($start), new IPv4IntervalPoint($end), $type);
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
     *   [10.0.1.0, 10.0.1.255]
     *   (10.0.0.0, 10.255.255.255]
     *   [172.16.0.0, 172.31.255.255)
     *   (192.168.0.0, 192.168.255.255)
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
            throw InvalidIntervalFormatException::create('[0.0.0.0, 255.255.255.255]', $string);
        }

        return self::create($match['start'], $match['end'], IntervalType::fromString($string));
    }

    /**
     * @param string $point
     *
     * @return bool
     */
    public function contains($point)
    {
        return $this->comparator->contains(new IPv4IntervalPoint($point));
    }

    /**
     * @param IPv4Interval $interval
     * @param bool $check_interval_type
     *
     * @return bool
     */
    public function intersects(IPv4Interval $interval, $check_interval_type = true)
    {
        return $this->comparator->intersects($interval, $check_interval_type);
    }

    /**
     * @param IPv4Interval $interval
     *
     * @return IPv4Interval|null
     */
    public function intersection(IPv4Interval $interval)
    {
        return $this->comparator->intersection($interval);
    }

    /**
     * The point is before the interval
     *
     * @param string $point
     *
     * @return bool
     */
    public function before($point)
    {
        return $this->comparator->before(new IPv4IntervalPoint($point));
    }

    /**
     * The point is after the interval
     *
     * @param string $point
     *
     * @return bool
     */
    public function after($point)
    {
        return $this->comparator->after(new IPv4IntervalPoint($point));
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
        return (string)$this->start;
    }

    /**
     * @return string
     */
    public function end()
    {
        return (string)$this->end;
    }

    /**
     * @return IPv4IntervalPoint
     */
    public function startPoint()
    {
        return $this->start;
    }

    /**
     * @return IPv4IntervalPoint
     */
    public function endPoint()
    {
        return $this->end;
    }

    /**
     * Returns a copy of this Interval with the start point altered.
     *
     * @param string $start
     *
     * @return self
     */
    public function withStart($start)
    {
        return self::create($start, $this->end(), $this->type);
    }

    /**
     * Returns a copy of this Interval with the end point altered.
     *
     * @param string $end
     *
     * @return self
     */
    public function withEnd($end)
    {
        return self::create($this->start(), $end, $this->type);
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
        return self::create($this->start(), $this->end(), $type);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->type->getReadable($this);
    }
}
