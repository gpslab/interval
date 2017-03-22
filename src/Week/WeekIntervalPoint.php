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
        $number = $monday->format('N');
        if ($number != 1) {
            $monday->modify('-'.($number - 1).' day');
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
