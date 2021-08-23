<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Month;

use GpsLab\Component\Interval\BaseIntervalPoint;

class MonthIntervalPoint extends BaseIntervalPoint
{
    /**
     * @var \DateTime
     */
    private $month;

    public function __construct(\DateTime $date)
    {
        $this->month = clone $date;
        $this->month->setDate($date->format('Y'), $date->format('m'), 1)->setTime(0, 0, 0);
    }

    /**
     * @return \DateTime
     */
    public function value()
    {
        return clone $this->month;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value()->format('Y/m');
    }
}
