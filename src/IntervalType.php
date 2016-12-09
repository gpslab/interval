<?php 
/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval;

use GpsLab\Component\Interval\Exception\IncorrectIntervalTypeException;
use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;

/**
 * @link https://en.wikipedia.org/wiki/Interval_(mathematics)
 */
final class IntervalType
{
    /**
     * @var int
     */
    const TYPE_START_EXCLUDED = 1;

    /**
     * @var int
     */
    const TYPE_END_EXCLUDED = 2;

    /**
     * [a, b]
     *
     * @var int
     */
    const TYPE_CLOSED = 0;

    /**
     * [a, b)
     *
     * @var int
     */
    const TYPE_HALF_CLOSED = self::TYPE_CLOSED | self::TYPE_END_EXCLUDED;

    /**
     * (a, b]
     *
     * @var int
     */
    const TYPE_HALF_OPEN = self::TYPE_CLOSED | self::TYPE_START_EXCLUDED;

    /**
     * (a, b)
     *
     * @var int
     */
    const TYPE_OPEN = self::TYPE_CLOSED | self::TYPE_START_EXCLUDED | self::TYPE_END_EXCLUDED;

    /**
     * @var IntervalType[]
     */
    private static $instances = [];

    /**
     * @var string
     */
    private $type = '';

    /**
     * @var array
     */
    private $formats = [
        self::TYPE_CLOSED => '[%s, %s]',
        self::TYPE_OPEN => '(%s, %s)',
        self::TYPE_HALF_CLOSED => '[%s, %s)',
        self::TYPE_HALF_OPEN => '(%s, %s]',
    ];

    /**
     * @param string $type
     */
    private function __construct($type)
    {
        $this->type = $type;
    }
    /**
     * @return int[]
     */
    public static function getAvailableValues()
    {
        return [
            self::TYPE_CLOSED,
            self::TYPE_OPEN,
            self::TYPE_HALF_CLOSED,
            self::TYPE_HALF_OPEN,
        ];
    }

    /**
     * @param int $value
     *
     * @return bool
     */
    public static function isValueSupported($value)
    {
        return in_array($value, self::getAvailableValues());
    }

    /**
     * @return int
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    private static function safe($value)
    {
        // limitation of count object instances
        if (!isset(self::$instances[$value])) {
            self::$instances[$value] = new self($value);
        }

        return self::$instances[$value];
    }

    /**
     * @param int $value
     *
     * @return self
     */
    public static function create($value)
    {
        if (!self::isValueSupported($value)) {
            throw IncorrectIntervalTypeException::create($value);
        }

        return self::safe($value);
    }

    /**
     * @return self
     */
    public static function closed()
    {
        return self::safe(self::TYPE_CLOSED);
    }

    /**
     * @return self
     */
    public static function halfClosed()
    {
        return self::safe(self::TYPE_HALF_CLOSED);
    }

    /**
     * @return self
     */
    public static function halfOpen()
    {
        return self::safe(self::TYPE_HALF_OPEN);
    }

    /**
     * @return self
     */
    public static function open()
    {
        return self::safe(self::TYPE_OPEN);
    }

    /**
     * @param string $string
     *
     * @return self
     */
    public static function fromString($string)
    {
        if (!preg_match('/^(\(|\[).+,.+(\)|\])$/', $string, $match)) {
            throw InvalidIntervalFormatException::create('[a, b]', $string);
        }

        $type = IntervalType::TYPE_CLOSED;

        if ($match[1] == '(') {
            $type |= IntervalType::TYPE_START_EXCLUDED;
        }

        if ($match[2] == ')') {
            $type |= IntervalType::TYPE_END_EXCLUDED;
        }

        return self::create($type);
    }

    /**
     * @param IntervalType $type
     *
     * @return bool
     */
    public function eq(IntervalType $type)
    {
        return $this->type() == $type->type();
    }

    /**
     * @return bool
     */
    public function startExcluded()
    {
        return ($this->type() & self::TYPE_START_EXCLUDED) == self::TYPE_START_EXCLUDED;
    }

    /**
     * @return bool
     */
    public function endExcluded()
    {
        return ($this->type() & self::TYPE_END_EXCLUDED) == self::TYPE_END_EXCLUDED;
    }

    /**
     * @param IntervalInterface $interval
     *
     * @return string
     */
    public function getReadable(IntervalInterface $interval)
    {
        return $this->format((string)$interval->startPoint(), (string)$interval->endPoint());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format('a', 'b');
    }

    /**
     * @param string $start
     * @param string $end
     *
     * @return string
     */
    private function format($start, $end)
    {
        return sprintf($this->formats[$this->type()], $start, $end);
    }
}
