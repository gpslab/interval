<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Tests\Interval\Persistence\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use GpsLab\Component\Interval\Date\DateInterval;
use GpsLab\Component\Interval\DateTime\DateTimeInterval;
use GpsLab\Component\Interval\IntervalInterface;
use GpsLab\Component\Interval\IPv4\IPv4Interval;
use GpsLab\Component\Interval\IPv4Network\IPv4Network;
use GpsLab\Component\Interval\Month\MonthInterval;
use GpsLab\Component\Interval\Number\NumberInterval;
use GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\BaseType;
use GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\DateIntervalType;
use GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\DateTimeIntervalType;
use GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\IPv4IntervalType;
use GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\IPv4NetworkType;
use GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\MonthIntervalType;
use GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\NumberIntervalType;
use GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\TimeIntervalType;
use GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\WeekIntervalType;
use GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\YearIntervalType;
use GpsLab\Component\Interval\Time\TimeInterval;
use GpsLab\Component\Interval\Week\WeekInterval;
use GpsLab\Component\Interval\Year\YearInterval;
use PHPUnit\Framework\TestCase;

class IntervalTypeTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|AbstractPlatform
     */
    private $platform;

    protected function setUp()
    {
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     *
     * @param string $class
     *
     * @return BaseType
     */
    private function getType($class)
    {
        if (Type::hasType($class)) {
            Type::overrideType($class, $class);
        } else {
            Type::addType($class, $class);
        }

        return Type::getType($class);
    }

    /**
     * @return array
     */
    public function getTypeNames()
    {
        return [
            [$this->getType(DateIntervalType::class), 'DateInterval'],
            [$this->getType(DateTimeIntervalType::class), 'DateTimeInterval'],
            [$this->getType(IPv4IntervalType::class), 'IPv4Interval'],
            [$this->getType(MonthIntervalType::class), 'MonthInterval'],
            [$this->getType(NumberIntervalType::class), 'NumberInterval'],
            [$this->getType(TimeIntervalType::class), 'TimeInterval'],
            [$this->getType(WeekIntervalType::class), 'WeekInterval'],
            [$this->getType(YearIntervalType::class), 'YearInterval'],
            [$this->getType(IPv4NetworkType::class), 'IPv4Network'],
        ];
    }

    /**
     * @dataProvider getTypeNames
     *
     * @param string $name
     */
    public function testGetName(BaseType $type, $name)
    {
        $this->assertEquals($name, $type->getName());
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return [
            [$this->getType(DateIntervalType::class)],
            [$this->getType(DateTimeIntervalType::class)],
            [$this->getType(IPv4IntervalType::class)],
            [$this->getType(MonthIntervalType::class)],
            [$this->getType(NumberIntervalType::class)],
            [$this->getType(TimeIntervalType::class)],
            [$this->getType(WeekIntervalType::class)],
            [$this->getType(YearIntervalType::class)],
            [$this->getType(IPv4NetworkType::class)],
        ];
    }

    /**
     * @dataProvider getTypes
     */
    public function testConvertToDatabaseValueNull(BaseType $type)
    {
        $this->assertNull($type->convertToDatabaseValue(null, $this->platform));
    }

    /**
     * @dataProvider getTypes
     */
    public function testConvertToPHPValueNull(BaseType $type)
    {
        $this->assertNull($type->convertToPHPValue(null, $this->platform));
    }

    /**
     * @return array
     */
    public function getTypeIntervals()
    {
        return [
            [
                $this->getType(DateIntervalType::class),
                DateInterval::class,
                '[2016-12-12, 2016-12-31]',
            ],
            [
                $this->getType(DateTimeIntervalType::class),
                DateTimeInterval::class,
                '[2016-12-12 17:41:20, 2016-12-31 17:41:50]',
            ],
            [
                $this->getType(IPv4IntervalType::class),
                IPv4Interval::class,
                '[192.168.0.0, 192.168.255.255]',
            ],
            [
                $this->getType(MonthIntervalType::class),
                MonthInterval::class,
                '[2016/12, 2017/04]',
            ],
            [
                $this->getType(NumberIntervalType::class),
                NumberInterval::class,
                '[-12, 4]',
            ],
            [
                $this->getType(TimeIntervalType::class),
                TimeInterval::class,
                '[17:41:20, 17:41:50]',
            ],
            [
                $this->getType(WeekIntervalType::class),
                WeekInterval::class,
                '[2016-12-12, 2016-12-26]',
            ],
            [
                $this->getType(YearIntervalType::class),
                YearInterval::class,
                '[2016, 2017]',
            ],
            [
                $this->getType(IPv4NetworkType::class),
                IPv4Network::class,
                '172.16.0.0/12',
            ],
        ];
    }

    /**
     * @dataProvider getTypeIntervals
     *
     * @param string $class
     * @param string $interval
     */
    public function testConvertToDatabaseValue(BaseType $type, $class, $interval)
    {
        $value = $this->getInterval($class, $interval);

        $this->assertEquals($interval, $type->convertToDatabaseValue($value, $this->platform));
    }

    /**
     * @dataProvider getTypeIntervals
     *
     * @param string $class
     * @param string $interval
     */
    public function testConvertToPHPValue(BaseType $type, $class, $interval)
    {
        $expected = $this->getInterval($class, $interval);

        $this->assertEquals($expected, $type->convertToPHPValue($interval, $this->platform));
    }

    /**
     * @param string $class
     * @param string $interval
     *
     * @return IntervalInterface
     */
    private function getInterval($class, $interval)
    {
        return call_user_func([$class, 'fromString'], $interval);
    }

    /**
     * @dataProvider getTypes
     */
    public function testRequiresSQLCommentHint(BaseType $type)
    {
        $this->assertTrue($type->requiresSQLCommentHint($this->platform));
    }
}
