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
     * @param string $string
     *
     * @return self
     */
    public static function fromString($string);

    /**
     * @return string
     */
    public function __toString();
}
