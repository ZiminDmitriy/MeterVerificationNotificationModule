<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\EntityFeature\AbstractFeature;

use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateType;
use InvalidArgumentException;

abstract class AbstractDateType extends DateType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof AbstractDate) {
            throw new InvalidArgumentException(
                sprintf(
                    "Instance must be of type %s, %s given.",
                    AbstractDate::class,
                    get_class($value)
                ), 0, null
            );
        }

        return $value->getValue();
    }
}