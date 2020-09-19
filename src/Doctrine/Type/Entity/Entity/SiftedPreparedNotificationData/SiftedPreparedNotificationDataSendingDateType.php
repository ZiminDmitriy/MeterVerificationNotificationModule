<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\Entity\SiftedPreparedNotificationData;

use App\Doctrine\Type\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractDateType;
use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\SendingDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

final class SiftedPreparedNotificationDataSendingDateType extends AbstractDateType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): SendingDate
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException(
                sprintf('$value in %s must be of type String', get_called_class()), 0, null
            );
        }

        return SendingDate::createFromString($value);
    }
}