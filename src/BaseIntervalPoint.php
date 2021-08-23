<?php
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval;

abstract class BaseIntervalPoint implements IntervalPointInterface
{
    /**
     * @return bool
     */
    public function eq(IntervalPointInterface $point)
    {
        return $this->value() == $point->value();
    }

    /**
     * @return bool
     */
    public function neq(IntervalPointInterface $point)
    {
        return $this->value() != $point->value();
    }

    /**
     * @return bool
     */
    public function lt(IntervalPointInterface $point)
    {
        return $this->value() < $point->value();
    }

    /**
     * @return bool
     */
    public function lte(IntervalPointInterface $point)
    {
        return $this->value() <= $point->value();
    }

    /**
     * @return bool
     */
    public function gt(IntervalPointInterface $point)
    {
        return $this->value() > $point->value();
    }

    /**
     * @return bool
     */
    public function gte(IntervalPointInterface $point)
    {
        return $this->value() >= $point->value();
    }
}
