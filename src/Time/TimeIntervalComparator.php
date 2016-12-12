<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Time;

use GpsLab\Component\Interval\IntervalType;

class TimeIntervalComparator
{
    /**
     * @var TimeInterval
     */
    private $interval;

    /**
     * @param TimeInterval $interval
     */
    public function __construct(TimeInterval $interval)
    {
        $this->interval = $interval;
    }

    /**
     * @param TimeIntervalPoint $point
     *
     * @return bool
     */
    public function contains(TimeIntervalPoint $point)
    {
        if ($this->interval->startPoint()->eq($point)) {
            return !$this->interval->type()->startExcluded();
        }

        if ($this->interval->endPoint()->eq($point)) {
            return !$this->interval->type()->endExcluded();
        }

        return $this->interval->startPoint()->lt($point) && $this->interval->endPoint()->gt($point);
    }

    /**
     * @param TimeInterval $interval
     * @param bool $check_interval_type
     *
     * @return bool
     */
    public function intersects(TimeInterval $interval, $check_interval_type = true)
    {
        if (
            $this->interval->startPoint()->gt($interval->endPoint()) ||
            $this->interval->endPoint()->lt($interval->startPoint())
        ) {
            return false;
        }

        if ($check_interval_type) {
            if ($this->interval->startPoint()->eq($interval->endPoint())) {
                return !$this->interval->type()->startExcluded() && !$interval->type()->endExcluded();
            }

            if ($this->interval->endPoint()->eq($interval->startPoint())) {
                return !$this->interval->type()->endExcluded() && !$interval->type()->startExcluded();
            }
        }

        return true;
    }

    /**
     * @param TimeInterval $interval
     *
     * @return TimeInterval|null
     */
    public function intersectInterval(TimeInterval $interval)
    {
        // intervals is not intersect or impossible create interval from one point
        if (
            $this->interval->startPoint()->gte($interval->endPoint()) ||
            $this->interval->endPoint()->lte($interval->startPoint())
        ) {
            // ignore closed intervals:
            // [a, b] | [b, c] = [b, b] = (b-1, b+1)
            return null;
        }

        $type = IntervalType::TYPE_CLOSED;

        if ($this->interval->startPoint()->lt($interval->startPoint())) {
            $start = $interval->startPoint();
            if ($interval->type()->startExcluded()) {
                $type |= IntervalType::TYPE_START_EXCLUDED;
            }
        } else {
            $start = $this->interval->startPoint();
            if ($this->interval->type()->startExcluded()) {
                $type |= IntervalType::TYPE_START_EXCLUDED;
            }
        }

        if ($this->interval->endPoint()->gt($interval->endPoint())) {
            $end = $interval->endPoint();
            if ($interval->type()->endExcluded()) {
                $type |= IntervalType::TYPE_END_EXCLUDED;
            }
        } else {
            $end = $this->interval->endPoint();
            if ($this->interval->type()->endExcluded()) {
                $type |= IntervalType::TYPE_END_EXCLUDED;
            }
        }

        return TimeInterval::create($start->value(), $end->value(), IntervalType::create($type));
    }

    /**
     * The point is before the interval
     *
     * @param TimeIntervalPoint $point
     *
     * @return bool
     */
    public function before(TimeIntervalPoint $point)
    {
        return $this->interval->startPoint()->gt($point);
    }

    /**
     * The point is after the interval
     *
     * @param TimeIntervalPoint $point
     *
     * @return bool
     */
    public function after(TimeIntervalPoint $point)
    {
        return $this->interval->endPoint()->lt($point);
    }
}
