<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Date;

use GpsLab\Component\Interval\BaseIntervalPoint;

class DateIntervalPoint extends BaseIntervalPoint
{
    /**
     * @var \DateTime
     */
    private $date;

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
     * @return string
     */
    public function __toString()
    {
        return $this->value()->format('Y-m-d');
    }
}
