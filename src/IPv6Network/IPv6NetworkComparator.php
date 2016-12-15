<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\IPv6Network;

/**
 * The comparator must be used only in IPv6Network.
 */
class IPv6NetworkComparator
{
    /**
     * @var IPv6Network
     */
    private $network;

    /**
     * @param IPv6Network $network
     */
    public function __construct(IPv6Network $network)
    {
        $this->network = $network;
    }

    /**
     * Checks if this network is equal to the specified network.
     *
     * @param IPv6Network $interval
     *
     * @return bool
     */
    public function equal(IPv6Network $interval)
    {
        return
            $this->network->startPoint()->eq($interval->startPoint()) &&
            $this->network->mask() == $interval->mask()
        ;
    }

    /**
     * Does this network contain the specified IP.
     *
     * @param IPv6NetworkPoint $point
     *
     * @return bool
     */
    public function contains(IPv6NetworkPoint $point)
    {
        return
            $this->network->startPoint()->lte($point) &&
            $this->network->endPoint()->gte($point)
        ;
    }

    /**
     * Does this network intersect the specified network.
     *
     * @param IPv6Network $network
     *
     * @return bool
     */
    public function intersects(IPv6Network $network)
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
     * @param IPv6Network $network
     *
     * @return bool
     */
    public function abuts(IPv6Network $network)
    {
        return
            $network->endPoint()->eq($this->network->startPoint()) ||
            $this->network->endPoint()->eq($network->startPoint())
        ;
    }
}
