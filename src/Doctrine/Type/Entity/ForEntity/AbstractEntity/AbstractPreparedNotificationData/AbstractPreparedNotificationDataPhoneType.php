<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData;

use App\Doctrine\Type\Entity\ForEntity\EntityFeature\Context\AbstractStringNullableContextType;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Phone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

final class AbstractPreparedNotificationDataPhoneType extends AbstractStringNullableContextType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): Phone
    {
        if (!is_null($value) && !is_string($value)) {
            throw new InvalidArgumentException(
                sprintf('$value in %s must be of type String', get_called_class()), 0, null
            );
        }

        return new Phone($value);
    }
}