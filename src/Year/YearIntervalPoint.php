<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Year;

use GpsLab\Component\Interval\BaseIntervalPoint;

class YearIntervalPoint extends BaseIntervalPoint
{
    /**
     * @var \DateTime
     */
    private $year;

    public function __construct(\DateTime $date)
    {
        $this->year = clone $date;
        $this->year->setDate($date->format('Y'), 1, 1)->setTime(0, 0, 0);
    }

    /**
     * @return \DateTime
     */
    public function value()
    {
        return clone $this->year;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value()->format('Y');
    }
}
