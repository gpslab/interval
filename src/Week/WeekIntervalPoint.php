<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Week;

use GpsLab\Component\Interval\BaseIntervalPoint;

class WeekIntervalPoint extends BaseIntervalPoint
{
    /**
     * @var \DateTime
     */
    private $week;

    /**
     * @param \DateTime $date
     */
    public function __construct(\DateTime $date)
    {
        $this->week = $this->getMondayThisWeek($date)->setTime(0, 0, 0);
    }

    /**
     * Bugfix for get monday of this week.
     *
     * @see https://bugs.php.net/bug.php?id=63740
     *
     * @param \DateTime $date
     *
     * @return \DateTime
     */
    private function getMondayThisWeek(\DateTime $date)
    {
        $monday = clone $date;

        if ($monday->format('N') != 1) {
            $monday->modify('Monday this week');

            if ($date->format('Y W') != $monday->format('Y W')) {
                $monday->modify('-7 day');
            }
        }

        return $monday;
    }

    /**
     * @return \DateTime
     */
    public function value()
    {
        return clone $this->week;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value()->format('Y-m-d');
    }
}
