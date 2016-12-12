<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval;

interface IntervalPointInterface
{
    /**
     * @return mixed
     */
    public function value();

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function eq(IntervalPointInterface $point);

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function neq(IntervalPointInterface $point);

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function lt(IntervalPointInterface $point);

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function lte(IntervalPointInterface $point);

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function gt(IntervalPointInterface $point);

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function gte(IntervalPointInterface $point);

    /**
     * @return string
     */
    public function __toString();
}
