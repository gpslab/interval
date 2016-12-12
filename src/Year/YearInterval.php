<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Year;

use GpsLab\Component\Interval\Exception\IncorrectIntervalException;
use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;
use GpsLab\Component\Interval\IntervalComparator;
use GpsLab\Component\Interval\ComparableIntervalInterface;
use GpsLab\Component\Interval\IntervalType;

class YearInterval implements ComparableIntervalInterface
{
    /**
     * @var string
     */
    const REGEXP = '/^
        (?:\(|\[)       # start type char
        \s*
        (?<start>\d{4}) # start point
        \s*,\s*         # separator
        (?<end>\d{4})   # end point
        \s*
        (?:\)|\])       # end type char
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
     * @var YearIntervalPoint
     */
    private $start;

    /**
     * @var YearIntervalPoint
     */
    private $end;

    /**
     * @param YearIntervalPoint $start
     * @param YearIntervalPoint $end
     * @param IntervalType $type
     */
    private function __construct(YearIntervalPoint $start, YearIntervalPoint $end, IntervalType $type)
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
        return new self(new YearIntervalPoint($start), new YearIntervalPoint($end), $type);
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
     *   [2016, 2017]
     *   (2015, 2016]
     *   [2014, 2015)
     *   (2013, 2014)
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
            throw InvalidIntervalFormatException::create('[YYYY, YYYY]', $string);
        }

        return self::create(
            (new \DateTime())->setDate($match['start'], 1, 1),
            (new \DateTime())->setDate($match['end'], 1, 1),
            IntervalType::fromString($string)
        );
    }

    /**
     * @param \DateTime $point
     *
     * @return bool
     */
    public function contains(\DateTime $point)
    {
        return $this->comparator->contains(new YearIntervalPoint($point));
    }

    /**
     * @param YearInterval $interval
     * @param bool $check_interval_type
     *
     * @return bool
     */
    public function intersects(YearInterval $interval, $check_interval_type = true)
    {
        return $this->comparator->intersects($interval, $check_interval_type);
    }

    /**
     * @param YearInterval $interval
     *
     * @return YearInterval|null
     */
    public function intersection(YearInterval $interval)
    {
        return $this->comparator->intersection($interval);
    }

    /**
     * The point is before the interval
     *
     * @param \DateTime $point
     *
     * @return bool
     */
    public function before(\DateTime $point)
    {
        return $this->comparator->before(new YearIntervalPoint($point));
    }

    /**
     * The point is after the interval
     *
     * @param \DateTime $point
     *
     * @return bool
     */
    public function after(\DateTime $point)
    {
        return $this->comparator->after(new YearIntervalPoint($point));
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
     * @return YearIntervalPoint
     */
    public function startPoint()
    {
        return $this->start;
    }

    /**
     * @return YearIntervalPoint
     */
    public function endPoint()
    {
        return $this->end;
    }

    /**
     * Returns a copy of this Interval with the start point altered.
     *
     * @param \DateTime $start
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
     * @param \DateTime $end
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
