<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\EntityFeature\Context;

use App\Entity\ForEntity\EntityFeature\Context\AbstractBooleanNotNullContext;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BooleanType;
use InvalidArgumentException;

abstract class AbstractBooleanNotNullContextType extends BooleanType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): bool
    {
        if (!$value instanceof AbstractBooleanNotNullContext) {
            throw new InvalidArgumentException(
                sprintf(
                    "Instance must be of type %s, %s given.",
                    AbstractBooleanNotNullContext::class,
                    get_class($value)
                ), 0, null
            );
        }

        return $value->getValue();
    }
}