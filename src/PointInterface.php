<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval;

interface PointInterface
{
    /**
     * @return mixed
     */
    public function value();

    /**
     * @return string
     */
    public function __toString();
}
