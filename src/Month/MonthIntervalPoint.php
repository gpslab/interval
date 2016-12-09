<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Month;

use GpsLab\Component\Interval\PointInterface;

class MonthIntervalPoint implements PointInterface
{
    /**
     * @var \DateTime
     */
    private $month;

    /**
     * @param \DateTime $date
     */
    public function __construct(\DateTime $date)
    {
        $this->month = clone $date;
        $this->month->modify('first day of this month')->setTime(0, 0, 0);
    }

    /**
     * @return \DateTime
     */
    public function value()
    {
        return clone $this->month;
    }

    /**
     * @param MonthIntervalPoint $point
     *
     * @return bool
     */
    public function eq(MonthIntervalPoint $point)
    {
        return $this->value() == $point->value();
    }

    /**
     * @param MonthIntervalPoint $point
     *
     * @return bool
     */
    public function neq(MonthIntervalPoint $point)
    {
        return $this->value() != $point->value();
    }

    /**
     * @param MonthIntervalPoint $point
     *
     * @return bool
     */
    public function lt(MonthIntervalPoint $point)
    {
        return $this->value() < $point->value();
    }

    /**
     * @param MonthIntervalPoint $point
     *
     * @return bool
     */
    public function lte(MonthIntervalPoint $point)
    {
        return $this->value() <= $point->value();
    }

    /**
     * @param MonthIntervalPoint $point
     *
     * @return bool
     */
    public function gt(MonthIntervalPoint $point)
    {
        return $this->value() > $point->value();
    }

    /**
     * @param MonthIntervalPoint $point
     *
     * @return bool
     */
    public function gte(MonthIntervalPoint $point)
    {
        return $this->value() >= $point->value();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value()->format('Y m');
    }
}
