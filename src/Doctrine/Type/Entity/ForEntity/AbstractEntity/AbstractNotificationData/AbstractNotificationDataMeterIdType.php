<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\AbstractEntity\AbstractNotificationData;

use App\Doctrine\Type\Entity\ForEntity\EntityFeature\Context\AbstractStringNotNullContextType;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

final class AbstractNotificationDataMeterIdType extends AbstractStringNotNullContextType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): MeterId
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException(
                sprintf('$value in %s must be of type String', get_called_class()), 0, null
            );
        }

        return new MeterId($value);
    }
}