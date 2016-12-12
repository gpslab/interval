<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Time;

use GpsLab\Component\Interval\BaseIntervalPoint;

class TimeIntervalPoint extends BaseIntervalPoint
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
     * @return string
     */
    public function __toString()
    {
        return $this->value()->format('H:i:s');
    }
}
