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
 * The comparator must be used only in IPv4Network.
 */
class IPv4NetworkComparator
{
    /**
     * @var IPv4Network
     */
    private $network;

    public function __construct(IPv4Network $network)
    {
        $this->network = $network;
    }

    /**
     * Checks if this network is equal to the specified network.
     *
     * @return bool
     */
    public function equal(IPv4Network $interval)
    {
        return
            $this->network->startPoint()->eq($interval->startPoint()) &&
            $this->network->mask()->equal($interval->mask())
        ;
    }

    /**
     * Does this network contain the specified IP.
     *
     * @return bool
     */
    public function contains(IPv4NetworkPoint $point)
    {
        return ($point->value() & $this->network->mask()->ip()) == $this->network->startPoint()->value();
    }

    /**
     * Does this network intersect the specified network.
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

    /**
     * Does this network abut with the network specified.
     *
     * @return bool
     */
    public function abuts(IPv4Network $network)
    {
        return
            $network->endPoint()->eq($this->network->startPoint()) ||
            $this->network->endPoint()->eq($network->startPoint())
        ;
    }
}
