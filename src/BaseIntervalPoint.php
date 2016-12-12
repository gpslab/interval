<?php
/**
 * Pkvs package
 *
 * @package Pkvs
 * @author  Peter Gribanov <pgribanov@1tv.com>
 */

namespace GpsLab\Component\Interval;

abstract class BaseIntervalPoint implements IntervalPointInterface
{
    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function eq(IntervalPointInterface $point)
    {
        return $this->value() == $point->value();
    }

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function neq(IntervalPointInterface $point)
    {
        return $this->value() != $point->value();
    }

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function lt(IntervalPointInterface $point)
    {
        return $this->value() < $point->value();
    }

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function lte(IntervalPointInterface $point)
    {
        return $this->value() <= $point->value();
    }

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function gt(IntervalPointInterface $point)
    {
        return $this->value() > $point->value();
    }

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function gte(IntervalPointInterface $point)
    {
        return $this->value() >= $point->value();
    }
}
