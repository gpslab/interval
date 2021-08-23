<?php
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval;

/**
 * This interface is needed only for a Comparator.
 */
interface ComparableIntervalInterface extends IntervalInterface
{
    /**
     * @return IntervalType
     */
    public function type();

    /**
     * Returns a copy of this Interval with the start point altered.
     *
     * @return self
     */
    public function withStart(IntervalPointInterface $start);

    /**
     * Returns a copy of this Interval with the end point altered.
     *
     * @return self
     */
    public function withEnd(IntervalPointInterface $end);

    /**
     * Returns a copy of this Interval with the interval type altered.
     *
     * @return self
     */
    public function withType(IntervalType $type);
}
