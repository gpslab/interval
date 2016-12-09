<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\DateTime;

use GpsLab\Component\Interval\PointInterface;

class DateTimeIntervalPoint implements PointInterface
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
    }

    /**
     * @return \DateTime
     */
    public function value()
    {
        return clone $this->date;
    }

    /**
     * @param DateTimeIntervalPoint $point
     *
     * @return bool
     */
    public function eq(DateTimeIntervalPoint $point)
    {
        return $this->value() == $point->value();
    }

    /**
     * @param DateTimeIntervalPoint $point
     *
     * @return bool
     */
    public function neq(DateTimeIntervalPoint $point)
    {
        return $this->value() != $point->value();
    }

    /**
     * @param DateTimeIntervalPoint $point
     *
     * @return bool
     */
    public function lt(DateTimeIntervalPoint $point)
    {
        return $this->value() < $point->value();
    }

    /**
     * @param DateTimeIntervalPoint $point
     *
     * @return bool
     */
    public function lte(DateTimeIntervalPoint $point)
    {
        return $this->value() <= $point->value();
    }

    /**
     * @param DateTimeIntervalPoint $point
     *
     * @return bool
     */
    public function gt(DateTimeIntervalPoint $point)
    {
        return $this->value() > $point->value();
    }

    /**
     * @param DateTimeIntervalPoint $point
     *
     * @return bool
     */
    public function gte(DateTimeIntervalPoint $point)
    {
        return $this->value() >= $point->value();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value()->format('Y-m-d H:i:s');
    }
}
