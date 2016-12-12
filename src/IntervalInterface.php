<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval;

interface IntervalInterface
{
    /**
     * @return IntervalType
     */
    public function type();

    /**
     * @return mixed
     */
    public function start();

    /**
     * @return mixed
     */
    public function end();

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
     * @param mixed $start
     *
     * @return self
     */
    public function withStart($start);

    /**
     * Returns a copy of this Interval with the end point altered.
     *
     * @param mixed $end
     *
     * @return self
     */
    public function withEnd($end);

    /**
     * Returns a copy of this Interval with the interval type altered.
     *
     * @param IntervalType $type
     *
     * @return self
     */
    public function withType(IntervalType $type);

    /**
     * @return string
     */
    public function __toString();
}
