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
    public function eq(self $point);

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function neq(self $point);

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function lt(self $point);

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function lte(self $point);

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function gt(self $point);

    /**
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function gte(self $point);

    /**
     * @return string
     */
    public function __toString();
}
