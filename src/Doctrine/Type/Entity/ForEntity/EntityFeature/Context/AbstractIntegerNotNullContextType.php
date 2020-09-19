<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\EntityFeature\Context;

use App\Entity\ForEntity\EntityFeature\Context\AbstractIntegerNotNullContext;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use InvalidArgumentException;

abstract class AbstractIntegerNotNullContextType extends IntegerType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if (is_null($value)) {
            return null;
        }

        if (!$value instanceof AbstractIntegerNotNullContext) {
            throw new InvalidArgumentException(
                sprintf(
                    "Instance must be of type %s, %s given.",
                    AbstractIntegerNotNullContext::class,
                    get_class($value)
                ), 0, null
            );
        }

        return $value->getValue();
    }
}