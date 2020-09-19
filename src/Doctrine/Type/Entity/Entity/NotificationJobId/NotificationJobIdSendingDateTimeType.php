<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\Entity\NotificationJobId;

use App\Doctrine\Type\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractDateTimeType;
use App\Entity\Entity\NotificationJobId\ForNotificationJobId\SendingDateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

final class NotificationJobIdSendingDateTimeType extends AbstractDateTimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): SendingDateTime
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException(
                sprintf('$value in %s must be of type String', get_called_class()), 0, null
            );
        }

        return SendingDateTime::createFromString($value);
    }
}