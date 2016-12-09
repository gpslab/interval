<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Year;

use GpsLab\Component\Interval\PointInterface;

class YearIntervalPoint implements PointInterface
{
    /**
     * @var \DateTime
     */
    private $year;

    /**
     * @param \DateTime $date
     */
    public function __construct(\DateTime $date)
    {
        $this->year = clone $date;
        $this->year->modify('first day of this year')->setTime(0, 0, 0);
    }

    /**
     * @return \DateTime
     */
    public function value()
    {
        return clone $this->year;
    }

    /**
     * @param YearIntervalPoint $point
     *
     * @return bool
     */
    public function eq(YearIntervalPoint $point)
    {
        return $this->value() == $point->value();
    }

    /**
     * @param YearIntervalPoint $point
     *
     * @return bool
     */
    public function neq(YearIntervalPoint $point)
    {
        return $this->value() != $point->value();
    }

    /**
     * @param YearIntervalPoint $point
     *
     * @return bool
     */
    public function lt(YearIntervalPoint $point)
    {
        return $this->value() < $point->value();
    }

    /**
     * @param YearIntervalPoint $point
     *
     * @return bool
     */
    public function lte(YearIntervalPoint $point)
    {
        return $this->value() <= $point->value();
    }

    /**
     * @param YearIntervalPoint $point
     *
     * @return bool
     */
    public function gt(YearIntervalPoint $point)
    {
        return $this->value() > $point->value();
    }

    /**
     * @param YearIntervalPoint $point
     *
     * @return bool
     */
    public function gte(YearIntervalPoint $point)
    {
        return $this->value() >= $point->value();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value()->format('Y');
    }
}
