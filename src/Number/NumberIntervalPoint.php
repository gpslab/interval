<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Number;

use GpsLab\Component\Interval\Exception\InvalidPointTypeException;
use GpsLab\Component\Interval\PointInterface;

class NumberIntervalPoint implements PointInterface
{
    /**
     * @var float|int
     */
    private $number;

    /**
     * @param int|float $number
     */
    public function __construct($number)
    {
        if (!is_numeric($number)) {
            throw InvalidPointTypeException::create('int|float', $number);
        }

        $this->number = $number;
    }

    /**
     * @return float|int
     */
    public function value()
    {
        return $this->number;
    }

    /**
     * @param NumberIntervalPoint $point
     *
     * @return bool
     */
    public function eq(NumberIntervalPoint $point)
    {
        return $this->value() == $point->value();
    }

    /**
     * @param NumberIntervalPoint $point
     *
     * @return bool
     */
    public function neq(NumberIntervalPoint $point)
    {
        return $this->value() != $point->value();
    }

    /**
     * @param NumberIntervalPoint $point
     *
     * @return bool
     */
    public function lt(NumberIntervalPoint $point)
    {
        return $this->value() < $point->value();
    }

    /**
     * @param NumberIntervalPoint $point
     *
     * @return bool
     */
    public function lte(NumberIntervalPoint $point)
    {
        return $this->value() <= $point->value();
    }

    /**
     * @param NumberIntervalPoint $point
     *
     * @return bool
     */
    public function gt(NumberIntervalPoint $point)
    {
        return $this->value() > $point->value();
    }

    /**
     * @param NumberIntervalPoint $point
     *
     * @return bool
     */
    public function gte(NumberIntervalPoint $point)
    {
        return $this->value() >= $point->value();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value();
    }
}
