<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval;

/**
 * The comparator must be used only in intervals for safe use the data types.
 */
class IntervalComparator
{
    /**
     * @var ComparableIntervalInterface
     */
    private $interval;

    /**
     * @param ComparableIntervalInterface $interval
     */
    public function __construct(ComparableIntervalInterface $interval)
    {
        $this->interval = $interval;
    }

    /**
     * Checks if this Interval is equal to the specified interval.
     *
     * @param ComparableIntervalInterface $interval
     *
     * @return bool
     */
    public function equal(ComparableIntervalInterface $interval)
    {
        return (
            $this->interval->startPoint()->eq($interval->startPoint()) &&
            $this->interval->endPoint()->eq($interval->endPoint()) &&
            $this->interval->type()->equal($interval->type())
        );
    }

    /**
     * Does this interval contain the specified point.
     *
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function contains(IntervalPointInterface $point)
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
     * Does this interval intersect the specified interval.
     *
     * @param ComparableIntervalInterface $interval
     *
     * @return bool
     */
    public function intersects(ComparableIntervalInterface $interval)
    {
        if (
            $this->interval->startPoint()->gt($interval->endPoint()) ||
            $this->interval->endPoint()->lt($interval->startPoint())
        ) {
            return false;
        }

        if ($this->interval->startPoint()->eq($interval->endPoint())) {
            return !$this->interval->type()->startExcluded() && !$interval->type()->endExcluded();
        }

        if ($this->interval->endPoint()->eq($interval->startPoint())) {
            return !$this->interval->type()->endExcluded() && !$interval->type()->startExcluded();
        }

        return true;
    }

    /**
     * Gets the intersection between this interval and another interval.
     *
     * @param ComparableIntervalInterface $interval
     *
     * @return ComparableIntervalInterface|null
     */
    public function intersection(ComparableIntervalInterface $interval)
    {
        // intervals is not intersect or impossible create interval from one point
        if (
            $this->interval->startPoint()->gte($interval->endPoint()) ||
            $this->interval->endPoint()->lte($interval->startPoint())
        ) {
            // ignore closed intervals:
            // [a, b] | [b, c] = [b, b]
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

        return $this->interval
            ->withStart($start)
            ->withEnd($end)
            ->withType(IntervalType::create($type));
    }

    /**
     * Gets the covered interval between this Interval and another interval.
     *
     * @param ComparableIntervalInterface $interval
     *
     * @return ComparableIntervalInterface
     */
    public function cover(ComparableIntervalInterface $interval)
    {
        $type = IntervalType::TYPE_CLOSED;
        if ($this->interval->startPoint()->lt($interval->startPoint())) {
            $start = $this->interval->startPoint();
            if ($interval->type()->startExcluded()) {
                $type |= IntervalType::TYPE_START_EXCLUDED;
            }
        } else {
            $start = $interval->startPoint();
            if ($this->interval->type()->startExcluded()) {
                $type |= IntervalType::TYPE_START_EXCLUDED;
            }
        }

        if ($this->interval->endPoint()->gt($interval->endPoint())) {
            $end = $this->interval->endPoint();
            if ($interval->type()->endExcluded()) {
                $type |= IntervalType::TYPE_END_EXCLUDED;
            }
        } else {
            $end = $interval->endPoint();
            if ($this->interval->type()->endExcluded()) {
                $type |= IntervalType::TYPE_END_EXCLUDED;
            }
        }

        return $this->interval
            ->withStart($start)
            ->withEnd($end)
            ->withType(IntervalType::create($type));
    }

    /**
     * Gets the gap between this interval and another interval.
     *
     * @param ComparableIntervalInterface $interval
     *
     * @return ComparableIntervalInterface|null
     */
    public function gap(ComparableIntervalInterface $interval)
    {
        if ($this->interval->startPoint()->gt($interval->endPoint())) {
            $type = IntervalType::TYPE_CLOSED;

            if (!$interval->type()->endExcluded()) { // invert exclude
                $type |= IntervalType::TYPE_START_EXCLUDED;
            }

            if (!$this->interval->type()->startExcluded()) { // invert exclude
                $type |= IntervalType::TYPE_END_EXCLUDED;
            }

            return $this->interval
                ->withStart($interval->endPoint())
                ->withEnd($this->interval->startPoint())
                ->withType(IntervalType::create($type));
        }

        if ($interval->startPoint()->gt($this->interval->endPoint())) {
            $type = IntervalType::TYPE_CLOSED;

            if (!$this->interval->type()->endExcluded()) { // invert exclude
                $type |= IntervalType::TYPE_START_EXCLUDED;
            }

            if (!$interval->type()->startExcluded()) { // invert exclude
                $type |= IntervalType::TYPE_END_EXCLUDED;
            }

            return $this->interval
                ->withStart($this->interval->endPoint())
                ->withEnd($interval->startPoint())
                ->withType(IntervalType::create($type));
        }

        return null; // no gap
    }

    /**
     * Does this interval abut with the interval specified.
     *
     * @param ComparableIntervalInterface $interval
     *
     * @return bool
     */
    public function abuts(ComparableIntervalInterface $interval)
    {
        return (
            $interval->endPoint()->eq($this->interval->startPoint()) ||
            $this->interval->endPoint()->eq($interval->startPoint())
        );
    }

    /**
     * Joins the interval between the adjacent.
     *
     * @param ComparableIntervalInterface $interval
     *
     * @return ComparableIntervalInterface|null
     */
    public function join(ComparableIntervalInterface $interval)
    {
        if (!$this->abuts($interval)) {
            return null;
        }

        return $this->cover($interval);
    }

    /**
     * Gets the union between this interval and another interval.
     *
     * @param ComparableIntervalInterface $interval
     *
     * @return ComparableIntervalInterface|null
     */
    public function union(ComparableIntervalInterface $interval)
    {
        if (!$this->intersects($interval)) {
            return null;
        }

        return $this->cover($interval);
    }

    /**
     * The point is before the interval.
     *
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function before(IntervalPointInterface $point)
    {
        return $this->interval->startPoint()->gt($point);
    }

    /**
     * The point is after the interval.
     *
     * @param IntervalPointInterface $point
     *
     * @return bool
     */
    public function after(IntervalPointInterface $point)
    {
        return $this->interval->endPoint()->lt($point);
    }
}
