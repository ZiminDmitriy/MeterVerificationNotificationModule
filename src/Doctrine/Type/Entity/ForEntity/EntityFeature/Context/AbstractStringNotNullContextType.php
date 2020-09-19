<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\EntityFeature\Context;

use App\Entity\ForEntity\EntityFeature\Context\AbstractStringNotNullContext;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use InvalidArgumentException;

abstract class AbstractStringNotNullContextType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (!$value instanceof AbstractStringNotNullContext) {
            throw new InvalidArgumentException(
                sprintf(
                    "Instance must be of type %s, %s given.",
                    AbstractStringNotNullContext::class,
                    get_class($value)
                ), 0, null
            );
        }

        return $value->getValue();
    }
}