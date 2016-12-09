<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Date;

use GpsLab\Component\Interval\PointInterface;

class DateIntervalPoint implements PointInterface
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
        $this->date->setTime(0, 0, 0);
    }

    /**
     * @return \DateTime
     */
    public function value()
    {
        return clone $this->date;
    }

    /**
     * @param DateIntervalPoint $point
     *
     * @return bool
     */
    public function eq(DateIntervalPoint $point)
    {
        return $this->value() == $point->value();
    }

    /**
     * @param DateIntervalPoint $point
     *
     * @return bool
     */
    public function neq(DateIntervalPoint $point)
    {
        return $this->value() != $point->value();
    }

    /**
     * @param DateIntervalPoint $point
     *
     * @return bool
     */
    public function lt(DateIntervalPoint $point)
    {
        return $this->value() < $point->value();
    }

    /**
     * @param DateIntervalPoint $point
     *
     * @return bool
     */
    public function lte(DateIntervalPoint $point)
    {
        return $this->value() <= $point->value();
    }

    /**
     * @param DateIntervalPoint $point
     *
     * @return bool
     */
    public function gt(DateIntervalPoint $point)
    {
        return $this->value() > $point->value();
    }

    /**
     * @param DateIntervalPoint $point
     *
     * @return bool
     */
    public function gte(DateIntervalPoint $point)
    {
        return $this->value() >= $point->value();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value()->format('Y-m-d');
    }
}
