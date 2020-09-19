<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\Entity\PreparedNotificationData;

use App\Doctrine\Type\Entity\ForEntity\EntityFeature\Context\AbstractStringNotNullContextType;
use App\Entity\Entity\PreparedNotificationData\ForPreparedNotificationData\Id;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

final class PreparedNotificationDataIdType extends AbstractStringNotNullContextType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Id
    {
        if (is_null($value)) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(
                sprintf('$value in %s must be of type String', get_called_class()), 0, null
            );
        }

        return Id::createFromString($value);
    }
}