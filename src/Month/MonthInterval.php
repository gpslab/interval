<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Month;

use GpsLab\Component\Interval\Exception\IncorrectIntervalException;
use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;
use GpsLab\Component\Interval\IntervalInterface;
use GpsLab\Component\Interval\IntervalType;

class MonthInterval implements IntervalInterface
{
    /**
     * @var string
     */
    const REGEXP = '/^
        (?:\(|\[)              # start type char
        \s*
        (?<start>\d{4}\/\d{2}) # start point
        \s*,\s*                # separator
        (?<end>\d{4}\/\d{2})   # end point
        \s*
        (?:\)|\])              # end type char
    $/x';

    /**
     * @var IntervalType
     */
    private $type;

    /**
     * @var MonthIntervalComparator
     */
    private $comparator;

    /**
     * @var MonthIntervalPoint
     */
    private $start;

    /**
     * @var MonthIntervalPoint
     */
    private $end;

    /**
     * @param MonthIntervalPoint $start
     * @param MonthIntervalPoint $end
     * @param IntervalType $type
     */
    private function __construct(MonthIntervalPoint $start, MonthIntervalPoint $end, IntervalType $type)
    {
        if ($start->gte($end)) {
            throw IncorrectIntervalException::create();
        }

        $this->type = $type;
        $this->start = $start;
        $this->end = $end;
        $this->comparator = new MonthIntervalComparator($this);
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
        return new self(new MonthIntervalPoint($start), new MonthIntervalPoint($end), $type);
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
     *   [2016/12, 2016/12]
     *   (2015/03, 2015/10]
     *   [2014/09, 2015/02)
     *   (2013/10, 2013/10)
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
            throw InvalidIntervalFormatException::create('[YYYY/MM, YYYY/MM]', $string);
        }

        return self::create(
            new \DateTime($match['start'].'/01'),
            new \DateTime($match['end'].'/01'),
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
        return $this->comparator->contains(new MonthIntervalPoint($point));
    }

    /**
     * @param MonthInterval $interval
     * @param bool $check_interval_type
     *
     * @return bool
     */
    public function intersects(MonthInterval $interval, $check_interval_type = true)
    {
        return $this->comparator->intersects($interval, $check_interval_type);
    }

    /**
     * @param MonthInterval $interval
     *
     * @return MonthInterval|null
     */
    public function intersection(MonthInterval $interval)
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
        return $this->comparator->before(new MonthIntervalPoint($point));
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
        return $this->comparator->after(new MonthIntervalPoint($point));
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
     * @return MonthIntervalPoint
     */
    public function startPoint()
    {
        return $this->start;
    }

    /**
     * @return MonthIntervalPoint
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
