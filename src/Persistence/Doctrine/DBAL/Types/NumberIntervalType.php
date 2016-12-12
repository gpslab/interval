<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types;

use GpsLab\Component\Interval\Number\NumberInterval;

class NumberIntervalType extends BaseType
{
    /**
     * @return string
     */
    protected function getClass()
    {
        return NumberInterval::class;
    }
}
