<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\EntityFeature\AbstractFeature;

use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractDateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeType;
use InvalidArgumentException;

abstract class AbstractDateTimeType extends DateTimeType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof AbstractDateTime) {
            throw new InvalidArgumentException(
                sprintf(
                    "Instance must be of type %s, %s given.",
                    AbstractDateTime::class,
                    get_class($value)
                ), 0, null
            );
        }

        return $value->getValue();
    }
}