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
use GpsLab\Component\Interval\IntervalInterface;
use GpsLab\Component\Interval\IntervalType;

class WeekInterval implements IntervalInterface
{
    /**
     * @var IntervalType
     */
    private $type;

    /**
     * @var WeekIntervalComparator
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
        $this->comparator = new WeekIntervalComparator($this);
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
     * @param \DateTime $point
     *
     * @return bool
     */
    public function contains(\DateTime $point)
    {
        return $this->comparator->contains(new WeekIntervalPoint($point));
    }

    /**
     * @param WeekInterval $interval
     * @param bool $check_interval_type
     *
     * @return bool
     */
    public function intersect(WeekInterval $interval, $check_interval_type = true)
    {
        return $this->comparator->intersect($interval, $check_interval_type);
    }

    /**
     * @param WeekInterval $interval
     *
     * @return WeekInterval|null
     */
    public function intersectInterval(WeekInterval $interval)
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
        return $this->comparator->before(new WeekIntervalPoint($point));
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
        return $this->comparator->after(new WeekIntervalPoint($point));
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
     * @return string
     */
    public function __toString()
    {
        return $this->type->getReadable($this);
    }
}
