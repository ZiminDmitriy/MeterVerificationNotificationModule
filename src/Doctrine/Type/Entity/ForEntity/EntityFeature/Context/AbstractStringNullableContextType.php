<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\EntityFeature\Context;

use App\Entity\ForEntity\EntityFeature\Context\AbstractStringNullableContext;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use InvalidArgumentException;

abstract class AbstractStringNullableContextType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (!$value instanceof AbstractStringNullableContext) {
            throw new InvalidArgumentException(
                sprintf(
                    "Instance must be of type %s, %s given.",
                    AbstractStringNullableContext::class,
                    get_class($value)
                ), 0, null
            );
        }

        return $value->getValue();
    }
}