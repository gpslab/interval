<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv4Network;

/**
 * The comparator must be used only in IPv4Network intervals.
 */
class IPv4NetworkComparator
{
    /**
     * @var IPv4Network
     */
    private $network;

    /**
     * @param IPv4Network $network
     */
    public function __construct(IPv4Network $network)
    {
        $this->network = $network;
    }

    /**
     * @param IPv4Network $interval
     *
     * @return bool
     */
    public function equal(IPv4Network $interval)
    {
        return (
            $this->network->startPoint()->eq($interval->startPoint()) &&
            $this->network->mask()->equal($interval->mask())
        );
    }

    /**
     * @param IPv4NetworkPoint $point
     *
     * @return bool
     */
    public function contains(IPv4NetworkPoint $point)
    {
        return ($point->value() & $this->network->mask()->ip()) == $this->network->startPoint()->value();
    }

    /**
     * @param IPv4Network $network
     *
     * @return bool
     */
    public function intersects(IPv4Network $network)
    {
        if (
            $this->network->startPoint()->gt($network->endPoint()) ||
            $this->network->endPoint()->lt($network->startPoint())
        ) {
            return false;
        }

        return true;
    }
}
