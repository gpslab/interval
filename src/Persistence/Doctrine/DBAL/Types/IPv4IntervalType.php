<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2016, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\TextType;
use GpsLab\Component\Interval\IPv4\IPv4Interval;
use GpsLab\Component\Interval\Exception\InvalidIntervalFormatException;

class IPv4IntervalType extends TextType
{
    /**
     * @param IPv4Interval|null $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof IPv4Interval ? (string)$value : null;
    }

    /**
     * @throws ConversionException
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return null|IPv4Interval
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        try {
            return IPv4Interval::fromString($value);
        } catch (InvalidIntervalFormatException $e) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'IPv4Interval';
    }
}
