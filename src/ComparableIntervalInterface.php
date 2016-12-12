<?php
/**
 * Pkvs package
 *
 * @package Pkvs
 * @author  Peter Gribanov <pgribanov@1tv.com>
 */

namespace GpsLab\Component\Interval;

interface ComparableIntervalInterface extends IntervalInterface
{
    /**
     * @return IntervalType
     */
    public function type();

    /**
     * @return IntervalPointInterface
     */
    public function startPoint();

    /**
     * @return IntervalPointInterface
     */
    public function endPoint();

    /**
     * Returns a copy of this Interval with the start point altered.
     *
     * @param IntervalPointInterface $start
     *
     * @return self
     */
    public function withStart(IntervalPointInterface $start);

    /**
     * Returns a copy of this Interval with the end point altered.
     *
     * @param IntervalPointInterface $end
     *
     * @return self
     */
    public function withEnd(IntervalPointInterface $end);

    /**
     * Returns a copy of this Interval with the interval type altered.
     *
     * @param IntervalType $type
     *
     * @return self
     */
    public function withType(IntervalType $type);
}
