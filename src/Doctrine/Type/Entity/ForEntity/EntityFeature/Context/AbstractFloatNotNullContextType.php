<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\EntityFeature\Context;

use App\Entity\ForEntity\EntityFeature\Context\AbstractFloatNotNullContext;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\FloatType;
use InvalidArgumentException;

abstract class AbstractFloatNotNullContextType extends FloatType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): float
    {
        if (!$value instanceof AbstractFloatNotNullContext) {
            throw new InvalidArgumentException(
                sprintf(
                    "Instance must be of type %s, %s given.",
                    AbstractFloatNotNullContext::class,
                    get_class($value)
                ), 0, null
            );
        }

        return $value->getValue();
    }
}