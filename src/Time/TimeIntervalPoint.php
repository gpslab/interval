<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Time;

use GpsLab\Component\Interval\IntervalPointInterface;

class TimeIntervalPoint implements IntervalPointInterface
{
    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @param \DateTime $date
     */
    public function __construct(\DateTime $date)
    {
        $this->date = clone $date;
        $this->date->setDate(1, 1, 1);
    }

    /**
     * @return \DateTime
     */
    public function value()
    {
        return clone $this->date;
    }

    /**
     * @param TimeIntervalPoint $point
     *
     * @return bool
     */
    public function eq(TimeIntervalPoint $point)
    {
        return $this->value() == $point->value();
    }

    /**
     * @param TimeIntervalPoint $point
     *
     * @return bool
     */
    public function neq(TimeIntervalPoint $point)
    {
        return $this->value() != $point->value();
    }

    /**
     * @param TimeIntervalPoint $point
     *
     * @return bool
     */
    public function lt(TimeIntervalPoint $point)
    {
        return $this->value() < $point->value();
    }

    /**
     * @param TimeIntervalPoint $point
     *
     * @return bool
     */
    public function lte(TimeIntervalPoint $point)
    {
        return $this->value() <= $point->value();
    }

    /**
     * @param TimeIntervalPoint $point
     *
     * @return bool
     */
    public function gt(TimeIntervalPoint $point)
    {
        return $this->value() > $point->value();
    }

    /**
     * @param TimeIntervalPoint $point
     *
     * @return bool
     */
    public function gte(TimeIntervalPoint $point)
    {
        return $this->value() >= $point->value();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value()->format('H:i:s');
    }
}
