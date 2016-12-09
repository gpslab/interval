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
use GpsLab\Component\Interval\IntervalInterface;
use GpsLab\Component\Interval\IntervalType;

class DateTimeInterval implements IntervalInterface
{
    /**
     * @var IntervalType
     */
    private $type;

    /**
     * @var DateTimeIntervalComparator
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

    /**
     * @param DateTimeIntervalPoint $start
     * @param DateTimeIntervalPoint $end
     * @param IntervalType $type
     */
    private function __construct(DateTimeIntervalPoint $start, DateTimeIntervalPoint $end, IntervalType $type)
    {
        if ($start->gte($end)) {
            throw IncorrectIntervalException::create();
        }

        $this->type = $type;
        $this->start = $start;
        $this->end = $end;
        $this->comparator = new DateTimeIntervalComparator($this);
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
        return new self(new DateTimeIntervalPoint($start), new DateTimeIntervalPoint($end), $type);
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
     * @param \DateTime $point
     *
     * @return bool
     */
    public function contains(\DateTime $point)
    {
        return $this->comparator->contains(new DateTimeIntervalPoint($point));
    }

    /**
     * @param DateTimeInterval $interval
     * @param bool $check_interval_type
     *
     * @return bool
     */
    public function intersect(DateTimeInterval $interval, $check_interval_type = true)
    {
        return $this->comparator->intersect($interval, $check_interval_type);
    }

    /**
     * @param DateTimeInterval $interval
     *
     * @return DateTimeInterval|null
     */
    public function intersectInterval(DateTimeInterval $interval)
    {
        return $this->comparator->intersectInterval($interval);
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
        return $this->comparator->before(new DateTimeIntervalPoint($point));
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
        return $this->comparator->after(new DateTimeIntervalPoint($point));
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
     * @return string
     */
    public function __toString()
    {
        return $this->type->getReadable($this);
    }
}
